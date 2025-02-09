<script setup>
import axios from 'axios';
import { reactive, onMounted, ref } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { useToastr } from '@/toastr';
import { Form } from 'vee-validate';
import FeaturesFormPictures from "./FeaturesFormPictures.vue"; // Import the child component

const router = useRouter();
const route = useRoute();
const toastr = useToastr();
const form = reactive({
    name: '',
    description: '',
    image: null, // For the file input
});

const handleSubmit = (values, actions) => {
    if (editMode.value) {
        editCategory(values, actions);
    } else {
        createCategory(values, actions);
    }
};

const createCategory = (values, actions) => {
    axios.post('/api/features/create', form)
        .then((response) => {
            router.push('/admin/features');
            toastr.success('Feature created successfully!');
        })
        .catch((error) => {
            actions.setErrors(error.response.data.errors);
        })
};

const editCategory = (values, actions) => {
    axios.put(`/api/features/${route.params.id}/edit`, form)
        .then((response) => {
            router.push('/admin/features');
            toastr.success('Feature updated successfully!');
        })
        .catch((error) => {
            actions.setErrors(error.response.data.errors);
        })
};


const getCategory = () => {
    axios.get(`/api/features/${route.params.id}/edit`)
        .then(({ data }) => {
            console.log(data);
            form.id = data.id;
            form.name = data.name;
            form.description = data.description;
            form.image = data.image;
            form.image_created = null;
        })
};


const editMode = ref(false);

onMounted(() => {
    if (route.name === 'admin.features.edit') {
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
    axios.post('/api/features/upload-image', formData)
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
                        Feature
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <router-link to="/admin/dashboard">Home</router-link>
                        </li>
                        <li class="breadcrumb-item">
                            <router-link to="/admin/features">Feature</router-link>
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

                                <button type="submit" class="btn btn-primary">Submit</button>
                            </Form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<features-form-pictures></features-form-pictures>

</template>
