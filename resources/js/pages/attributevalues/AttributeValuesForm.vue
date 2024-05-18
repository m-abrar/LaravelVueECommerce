<script setup>
import axios from 'axios';
import { reactive, onMounted, ref } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { useToastr } from '@/toastr';
import { Form } from 'vee-validate';
import AttributeValuesFormPictures from "./AttributeValuesFormPictures.vue"; // Import the child component

const router = useRouter();
const route = useRoute();
const toastr = useToastr();
const form = reactive({
    attribute_id: '',
    name: '',
    description: '',
    image: null, // For the file input
});

const handleSubmit = (values, actions) => {
    if (editMode.value) {
        editAttributeValue(values, actions);
    } else {
        createAttributeValue(values, actions);
    }
};

const createAttributeValue = (values, actions) => {
    axios.post('/api/attributevalues/create', form)
        .then((response) => {
            router.push('/admin/attributevalues');
            toastr.success('Attribute Value created successfully!');
        })
        .catch((error) => {
            actions.setErrors(error.response.data.errors);
        })
};

const editAttributeValue = (values, actions) => {
    axios.put(`/api/attributevalues/${route.params.id}/edit`, form)
        .then((response) => {
            router.push('/admin/attributevalues');
            toastr.success('Attribute Value updated successfully!');
        })
        .catch((error) => {
            actions.setErrors(error.response.data.errors);
        })
};

const getAttributeValue = () => {
    axios.get(`/api/attributevalues/${route.params.id}/edit`)
        .then(({ data }) => {
            console.log(data);
            form.id = data.id;
            form.attribute_id = data.attribute_id;
            form.name = data.name;
            form.description = data.description;
            form.image = data.image;
            form.image_created = null;
        })
};

const availableAttributes = ref([]);

const getAvailableAttributes = () => {
    axios.get("/api/attributes").then((response) => {
        availableAttributes.value = response.data;
    });
};

const editMode = ref(false);

onMounted(() => {
    if (route.name === 'admin.attributevalues.edit') {
        editMode.value = true;
        getAttributeValue();
    }
    getAvailableAttributes();
});

const fileInput = ref(null);

const openFileInput = () => {
    fileInput.value.click();
};

const handleFileChange = async (event) => {
    const file = event.target.files[0];
    form.image = URL.createObjectURL(file); // Set the file directly in your reactive form

    // Create a FormData object and append the file to it
    const formData = new FormData();
    formData.append('image', file);
    formData.append('id', form.id);

    // Send the FormData to your server for processing
    axios.post('/api/attributevalues/upload-image', formData)
        .then((response) => {
            console.log(response);
            form.image_created = response.data.image_created;
            toastr.success('Image uploaded successfully!');
        })
        .catch((error) => {
            toastr.error('Image upload failed.');
        });
};
</script>

<template> 
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">
                        <span v-if="editMode">Edit</span>
                        <span v-else>Create</span>
                        Attribute Value
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <router-link to="/admin/dashboard">Home</router-link>
                        </li>
                        <li class="breadcrumb-item">
                            <router-link to="/admin/attributevalues">Attribute Value</router-link>
                        </li>
                        <li class="breadcrumb-item active">
                            <span v-if="editMode">Edit</span>
                            <span v-else>Create</span>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <Form @submit="handleSubmit" v-slot="{ errors }">
                                <div class="form-group">
                                    <label for="attributeId">Attribute</label>
                                    <!-- You can create a select element for property types here -->
                                    <select v-model="form.attribute_id" id="attributeId"
                                        class="form-control"
                                        :class="{ 'is-invalid': errors.attribute_id }">
                                        <option value="" disabled>Select Attribue</option>
                                        <!-- Populate options based on availableAttributes -->
                                        <option v-for="attribute in availableAttributes"
                                            :value="attribute.id" :key="attribute.id">{{
                                                attribute.name }}</option>
                                    </select>
                                    <span class="invalid-feedback">{{ errors.attribute_id }}</span>
                                </div>
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input v-model="form.name" type="text" class="form-control"
                                        :class="{ 'is-invalid': errors.name }" id="name" placeholder="Enter Name">
                                    <span class="invalid-feedback">{{ errors.name }}</span>
                                </div>
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea v-model="form.description" class="form-control"
                                        :class="{ 'is-invalid': errors.description }" id="description" rows="3"
                                        placeholder="Enter Description"></textarea>
                                    <span class="invalid-feedback">{{ errors.description }}</span>
                                </div>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </Form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <attributevalues-form-pictures></attributevalues-form-pictures>
</template>
