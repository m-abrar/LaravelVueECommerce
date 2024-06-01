<script setup>
import axios from "axios";
import { reactive, onMounted, ref } from "vue";
import { useRouter, useRoute } from "vue-router";
import { useToastr } from "@/toastr";
import { Form, Field, ErrorMessage } from "vee-validate";
import CategoryFormPictures from "./CategoryFormPictures.vue";

const router = useRouter();
const route = useRoute();
const toastr = useToastr();
const form = reactive({
    name: "",
    parent_id: "",
    slug: "",
    description: "",
    alt_text: "",
    meta_title: "",
    meta_description: "",
    meta_keywords: "",
    is_active: false,
    image: null,
    banner: null, // Added banner field
});
const availableCategories = ref([]);

const getAvailableCategories = () => {
    axios.get("/api/categories/parents").then((response) => {
        availableCategories.value = response.data;
    });
};

const handleSubmit = (values, actions) => {
    if (editMode.value) {
        editCategory(values, actions);
    } else {
        createCategory(values, actions);
    }
};

const createCategory = (values, actions) => {
    axios
        .post("/api/categories/create", form)
        .then(() => {
            router.push("/admin/categories");
            toastr.success("Category created successfully!");
        })
        .catch((error) => {
            actions.setErrors(error.response.data.errors);
        });
};

const editCategory = (values, actions) => {
    axios
        .put(`/api/categories/${route.params.id}/edit`, form)
        .then(() => {
            router.push("/admin/categories");
            toastr.success("Category updated successfully!");
        })
        .catch((error) => {
            actions.setErrors(error.response.data.errors);
        });
};

const getCategory = () => {
    axios.get(`/api/categories/${route.params.id}/edit`).then(({ data }) => {
        // Set form fields with retrieved data
        form.id = data.id;
        form.parent_id = data.parent_id !== null || data.parent_id !== 0 ? data.parent_id : "0"; 
        form.name = data.name;
        form.slug = data.slug;
        form.description = data.description;
        form.alt_text = data.alt_text;
        form.meta_title = data.meta_title;
        form.meta_description = data.meta_description;
        form.meta_keywords = data.meta_keywords;
        form.is_active = data.is_active;
        form.image = data.image;
        form.banner = data.banner; // Set banner field
    });
};

const editMode = ref(false);

onMounted(() => {
    if (route.name === "admin.categories.edit") {
        editMode.value = true;
        getCategory();
    }

    getAvailableCategories();
});

const handleImageChange = async (event) => {
    const file = event.target.files[0];
    form.image = URL.createObjectURL(file); 

    const formData = new FormData();
    formData.append("image", file);
    formData.append("id", form.id);

    try {
        const response = await axios.post("/api/categories/upload-image", formData);
        form.image_created = response.data.image_created;
        toastr.success("Image uploaded successfully!");
    } catch (error) {
        toastr.error("Image upload failed.");
    }
};

const handleBannerChange = async (event) => {
    const file = event.target.files[0];
    form.banner = URL.createObjectURL(file); 

    const formData = new FormData();
    formData.append("banner", file);
    formData.append("id", form.id);

    try {
        const response = await axios.post("/api/categories/upload-banner", formData);
        form.banner_created = response.data.banner_created;
        toastr.success("Banner uploaded successfully!");
    } catch (error) {
        toastr.error("Banner upload failed.");
    }
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
                        Category
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <router-link to="/admin/dashboard">Home</router-link>
                        </li>
                        <li class="breadcrumb-item">
                            <router-link to="/admin/categories">Categories</router-link>
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
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">Name</label>
                                            <Field v-model="form.name" type="text" class="form-control"
                                                :class="{ 'is-invalid': errors.name }" id="name"
                                                placeholder="Enter Name" name="name" />
                                            <ErrorMessage name="name" class="invalid-feedback" />
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="slug">Slug</label>
                                            <Field v-model="form.slug" type="text" class="form-control"
                                                :class="{ 'is-invalid': errors.slug }" id="slug"
                                                placeholder="Enter slug" name="slug" />
                                            <ErrorMessage name="slug" class="invalid-feedback" />
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="parent_id">Parent Category</label>
                                            <select v-model="form.parent_id" id="parent_id" class="form-control"
                                                :class="{ 'is-invalid': errors.parent_id }">
                                                <option value="" disabled>Select Category</option>
                                                <option value="0">No Parent Category</option> <!-- Option to select no parent category -->
                                                <!-- Populate options based on categories -->
                                                <option v-for="category in availableCategories" :value="category.id"
                                                    :key="category.id">{{ category.name }}</option>
                                            </select>
                                            <ErrorMessage name="parent_id" class="invalid-feedback" />
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="form-check">
                                                <label for="is_active">
                                                    <input v-model="form.is_active" class="form-check-input"
                                                        type="checkbox" id="is_active" />
                                                    Active
                                                </label>
                                            </div>
                                            <ErrorMessage name="is_active" class="invalid-feedback" />
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            <Field v-model="form.description" class="form-control" :class="{
                                                'is-invalid': errors.description,
                                            }" id="description" rows="3" placeholder="Enter Description"
                                                name="description" as="textarea" />
                                            <ErrorMessage name="description" class="invalid-feedback" />
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="alt_text">Image Alt Text</label>
                                            <Field v-model="form.alt_text" type="text" class="form-control"
                                                :class="{ 'is-invalid': errors.alt_text }" id="alt_text"
                                                placeholder="Enter Alt Text" name="alt_text" />
                                            <ErrorMessage name="alt_text" class="invalid-feedback" />
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="meta_title">Meta Title</label>
                                            <Field v-model="form.meta_title" type="text" class="form-control"
                                                :class="{ 'is-invalid': errors.meta_title }" id="meta_title"
                                                placeholder="Enter meta title" name="meta_title" />
                                            <ErrorMessage name="meta_title" class="invalid-feedback" />
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="meta_description">Meta Description</label>
                                            <Field v-model="form.meta_description" class="form-control" :class="{
                                                'is-invalid': errors.meta_description,
                                            }" id="meta_description" rows="3" placeholder="Enter meta description"
                                                name="meta_description" as="textarea" />
                                            <ErrorMessage name="meta_description" class="invalid-feedback" />
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="meta_keywords">Meta Keywords</label>
                                            <Field v-model="form.meta_keywords" class="form-control" :class="{
                                                'is-invalid': errors.meta_keywords,
                                            }" id="meta_keywords" rows="3" placeholder="Enter meta keywords"
                                                name="meta_keywords" as="textarea" />
                                            <ErrorMessage name="meta_keywords" class="invalid-feedback" />
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                        <label for="image">Image</label>
                                        <input type="file" @change="handleImageChange" class="form-control-file" id="image" name="image">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                        <label for="banner">Banner</label>
                                        <input type="file" @change="handleBannerChange" class="form-control-file" id="banner" name="banner">
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                            </Form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <CategoryFormPictures />
</template>
