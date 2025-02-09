<script setup>
import axios from 'axios';
import { reactive, onMounted, ref } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { useToastr } from '@/toastr';
import { Form } from 'vee-validate';
import AttributesFormPictures from "./AttributesFormPictures.vue"; // Import the child component

const router = useRouter();
const route = useRoute();
const toastr = useToastr();
const form = reactive({
    name: '',
    description: '',
    image: null, // For the file input
    display_type: 'checkbox', // Add display_type to form data
    attributes: [], // Assuming you have some attributes to display
});

const handleSubmit = (values, actions) => {
    if (editMode.value) {
        editCategory(values, actions);
    } else {
        createCategory(values, actions);
    }
};

const createCategory = (values, actions) => {
    axios.post('/api/attributes/create', form)
        .then((response) => {
            router.push('/admin/attributes');
            toastr.success('Attribute created successfully!');
        })
        .catch((error) => {
            actions.setErrors(error.response.data.errors);
        })
};

const editCategory = (values, actions) => {
    axios.put(`/api/attributes/${route.params.id}/edit`, form)
        .then((response) => {
            router.push('/admin/attributes');
            toastr.success('Attribute updated successfully!');
        })
        .catch((error) => {
            actions.setErrors(error.response.data.errors);
        })
};

const getCategory = () => {
    axios.get(`/api/attributes/${route.params.id}/edit`)
        .then(({ data }) => {
            form.id = data.id;
            form.name = data.name;
            form.display_type = data.display_type;
            form.description = data.description;
            form.image = data.image;
            form.image_created = null;
            form.attributes = data.attributes; // Load existing attributes
        })
};

const editMode = ref(false);

onMounted(() => {
    if (route.name === 'admin.attributes.edit') {
        editMode.value = true;
        getCategory();
    }
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
    axios.post('/api/attributes/upload-image', formData)
        .then((response) => {
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
                        Attribute
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <router-link to="/admin/dashboard">Home</router-link>
                        </li>
                        <li class="breadcrumb-item">
                            <router-link to="/admin/attributes">Attribute</router-link>
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

                                <div class="form-group">
                                    <label for="display_type">Display Type</label>
                                    <select v-model="form.display_type" class="form-control" id="display_type">
                                        <option value="checkbox">Checkboxes</option>
                                        <option value="radio">Radio Buttons</option>
                                    </select>
                                </div>

                                <button type="submit" class="btn btn-primary">Submit</button>
                            </Form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <attributes-form-pictures></attributes-form-pictures>
</template>
