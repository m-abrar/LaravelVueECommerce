<script setup>
import axios from "axios";
import { reactive, onMounted, ref } from "vue";
import { useRouter, useRoute } from "vue-router";
import { useToastr } from "@/toastr";
import { Form } from "vee-validate";
import flatpickr from "flatpickr";
import "flatpickr/dist/themes/light.css";
import ProductsFormPictures from "./ProductsFormPictures.vue"; // Import the child component

const router = useRouter();
const route = useRoute();
const toastr = useToastr();

const form = reactive({
    name: "",
    slug: "",
    item_code: "",
    description: "",
    attributes: [],
    price: "",
    category_id: "",
    excerpt: "",
    categories: [],
    image: "placeholder.png", // Default image
    image_id: "",
    sort_order: "",
    is_active: true, // Set default value
    is_featured: false,
    is_new: true,
    is_hot: false,
    is_special: false,
    user_id: "",
});

const handleSubmit = (values, actions) => {
    if (editMode.value) {
        editProduct(values, actions);
    } else {
        createProduct(values, actions);
    }
};

const createProduct = (values, actions) => {
    axios
        .post("/api/products/create", form)
        .then((response) => {
            router.push("/admin/products");
            toastr.success("Product created successfully!");
        })
        .catch((error) => {
            actions.setErrors(error.response.data.errors);
        });
};

const editProduct = (values, actions) => {
    axios
        .put(`/api/products/${route.params.id}/edit`, form)
        .then((response) => {
            router.push("/admin/products");
            toastr.success("Product updated successfully!");
        })
        .catch((error) => {
            actions.setErrors(error.response.data.errors);
        });
};

const categories = ref();

const getAvailableCategories = () => {
    axios.get("/api/categories").then((response) => {
        availableCategories.value = response.data;
    });
};



const availableAttributes = ref();
const getAvailableAttributes = () => {
    axios.get("/api/attributes").then((response) => {
        availableAttributes.value = response.data;
    });
};

const availableCategories = ref();

const getProduct = () => {
    axios.get(`/api/products/${route.params.id}/edit`).then(({ data }) => {
        form.name = data.name;
        form.slug = data.slug;
        form.item_code = data.item_code;
        form.description = data.description;
        form.category_id = data.category_id;
        form.excerpt = data.excerpt;
        form.attributes = data.associated_attributes;
        form.features = data.associated_features;
        form.categories = data.associated_categories;
        form.image = data.image;
        form.image_id = data.image_id;
        form.sort_order = data.sort_order;
        form.is_active = data.is_active ? true : false;
        form.is_featured = data.is_featured ? true : false;
        form.is_new = data.is_new ? true : false;
        form.is_hot = data.is_hot ? true : false;
        form.is_special = data.is_special ? true : false;
        form.user_id = data.user_id;
    });
};

const editMode = ref(false);

onMounted(() => {
    if (route.name === "admin.products.edit") {
        editMode.value = true;
        getProduct();
    }

    flatpickr(".flatpickr", {
        enableTime: true,
        dateFormat: "Y-m-d h:i K",
        defaultHour: 10,
    });
    getAvailableAttributes();
    getAvailableCategories();
});
</script>

<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <router-link :to="{ name: 'admin.products' }" class="btn btn-outline-primary">
                        <i class="fas fa-list"></i> All Products
                    </router-link>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <router-link to="/admin/dashboard">Home</router-link>
                        </li>
                        <li class="breadcrumb-item">
                            <router-link to="/admin/products">Products</router-link>
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
                    <Form @submit="handleSubmit" v-slot="{ errors }">
                        <div class="card card-success card-outline">
                            <div class="card-header">
                                <h3 v-if="editMode">Edit: {{ form.name }}</h3>
                                <h3 v-else>Add New Product</h3>
                            </div>

                            <!-- /.card-header -->
                            <div class="card-body">
                                <ul class="nav nav-pills">
                                    <li class="nav-item">
                                        <a class="nav-link active show" href="#basic-detail" data-toggle="tab">Basic
                                            Details</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#address" data-toggle="tab">SEO</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#description" data-toggle="tab">Description</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#attributes" data-toggle="tab">Attributes</a>
                                    </li>
                                </ul>
                                <hr />
                                <div class="tab-content">
                                    <!-- Tab Tab -->
                                    <div class="tab-pane active show" id="basic-detail">
                                        <h3 class="text-center">
                                            Primary Details
                                        </h3>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="name">Product Name</label>
                                                    <input v-model="form.name" type="text" class="form-control" :class="{
                                                        'is-invalid':
                                                            errors.name,
                                                    }" id="name" placeholder="Enter Product Name" />
                                                    <span class="invalid-feedback">{{ errors.name }}</span>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="slug">SKU/Item Code</label>
                                                    <input v-model="form.item_code" type="text" class="form-control"
                                                        :class="{
                                                            'is-invalid':
                                                                errors.item_code,
                                                        }" id="item_code" placeholder="Enter Item Code" />
                                                    <span class="invalid-feedback">{{
                                                        errors.item_code
                                                    }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="slug">Slug</label>
                                                    <input v-model="form.slug" type="text" class="form-control" :class="{
                                                        'is-invalid':
                                                            errors.slug,
                                                    }" id="slug" placeholder="Enter Slug" />
                                                    <span class="invalid-feedback">{{ errors.slug }}</span>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="categoryId">Category</label>
                                                    <!-- You can create a select element for property types here -->
                                                    <select v-model="form.category_id" id="categoryId"
                                                        class="form-control"
                                                        :class="{ 'is-invalid': errors.category_id }">
                                                        <option value="" disabled>Select Property Type</option>
                                                        <!-- Populate options based on categories -->
                                                        <option v-for="category in availableCategories"
                                                            :value="category.id" :key="category.id">{{
                                                                category.name }}</option>
                                                    </select>
                                                    <span class="invalid-feedback">{{ errors.category_id }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <div class="form-check">
                                                        <label for="is_active">
                                                            <input v-model="form.is_active
                                                                " class="form-check-input" type="checkbox"
                                                                id="is_active" />
                                                            Active</label>
                                                    </div>
                                                    <span class="invalid-feedback">{{
                                                        errors.is_active
                                                    }}</span>
                                                </div>
                                            </div>

                                            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-6">
                                                <div class="form-group">
                                                    <div class="form-check">
                                                        <label for="is_featured">
                                                            <input v-model="form.is_featured
                                                                " class="form-check-input" type="checkbox"
                                                                id="is_featured" />
                                                            Featured</label>
                                                    </div>
                                                    <span class="invalid-feedback">{{
                                                        errors.is_featured
                                                    }}</span>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-6">
                                                <div class="form-group">
                                                    <div class="form-check">
                                                        <label for="is_special">
                                                            <input v-model="form.is_special
                                                                " class="form-check-input" type="checkbox"
                                                                id="is_special" />
                                                            Special</label>
                                                    </div>
                                                    <span class="invalid-feedback">{{
                                                        errors.is_special
                                                    }}</span>
                                                </div>
                                            </div>

                                            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-6">
                                                <div class="form-group">
                                                    <div class="form-check">
                                                        <label for="is_hot">
                                                            <input v-model="form.is_hot
                                                                " class="form-check-input" type="checkbox"
                                                                id="is_hot" />
                                                            Hot</label>
                                                    </div>
                                                    <span class="invalid-feedback">{{
                                                        errors.is_hot
                                                    }}</span>
                                                </div>
                                            </div>

                                            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-6">
                                                <div class="form-group">
                                                    <div class="form-check">
                                                        <label for="is_new">
                                                            <input v-model="form.is_new
                                                                " class="form-check-input" type="checkbox"
                                                                id="is_new" />
                                                            New</label>
                                                    </div>
                                                    <span class="invalid-feedback">{{
                                                        errors.is_new
                                                    }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <h3 class="text-center">Categories</h3>

                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <!-- Iterate through available categories -->
                                                    <div class="checkbox-item col-lg-3 col-md-4 col-sm-6 col-sm-12"
                                                        v-for="category in availableCategories" :key="category.id">
                                                        <label class="checkbox-label">
                                                            <input type="checkbox" v-model="form.categories
                                                                " :value="category.id
                                                                    " name="categories[]" />
                                                            {{ category.name }}
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Tab Pan-->
                                    <div class="tab-pane" id="address">
                                        <h3 class="text-center">SEO</h3>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="address">Meta Tag Title</label>
                                                    <input v-model="form.address" type="text" class="form-control"
                                                        :class="{
                                                            'is-invalid':
                                                                errors.address,
                                                        }" id="address" placeholder="Enter Product Address" />
                                                    <span class="invalid-feedback">{{
                                                        errors.address
                                                    }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="address">Meta Tag
                                                        Description</label>
                                                    <input v-model="form.address" type="text" class="form-control"
                                                        :class="{
                                                            'is-invalid':
                                                                errors.address,
                                                        }" id="address" placeholder="Enter Product Address" />
                                                    <span class="invalid-feedback">{{
                                                        errors.address
                                                    }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="address">Meta Tag
                                                        Keywords</label>
                                                    <input v-model="form.address" type="text" class="form-control"
                                                        :class="{
                                                            'is-invalid':
                                                                errors.address,
                                                        }" id="address" placeholder="Enter Product Address" />
                                                    <span class="invalid-feedback">{{
                                                        errors.address
                                                    }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="address">Product Tags</label>
                                                    <input v-model="form.address" type="text" class="form-control"
                                                        :class="{
                                                            'is-invalid':
                                                                errors.address,
                                                        }" id="address" placeholder="Enter Product Address" />
                                                    <span class="invalid-feedback">{{
                                                        errors.address
                                                    }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Tab Pan-->
                                    <div class="tab-pane" id="description">
                                        <h3 class="text-center">Description</h3>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="excerpt">Excerpt</label>
                                                    <textarea v-model="form.excerpt" class="form-control" :class="{
                                                        'is-invalid':
                                                            errors.excerpt,
                                                    }" id="excerpt" rows="3"
                                                        placeholder="Enter Excerpt"></textarea>
                                                    <span class="invalid-feedback">{{
                                                        errors.excerpt
                                                    }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="description">Description</label>
                                                    <textarea v-model="form.description
                                                        " class="form-control" :class="{
                                                            'is-invalid':
                                                                errors.description,
                                                        }" id="description" rows="3"
                                                        placeholder="Enter product description here..."></textarea>
                                                    <span class="invalid-feedback">{{
                                                        errors.description
                                                    }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Tab Pan-->
                                    <div class="tab-pane" id="attributes">
                                        <h3 class="text-center">Attributes</h3>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <!-- Iterate through available attributes -->
                                                    <div class="checkbox-item col-lg-3 col-md-4 col-sm-6 col-sm-12"
                                                        v-for="attribute in availableAttributes" :key="attribute.id">
                                                        <label class="checkbox-label">
                                                            <input type="checkbox" v-model="form.attributes
                                                                " :value="attribute.id
                                                                    " name="attributes[]" />
                                                            {{ attribute.name }}
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button v-if="editMode" type="submit" class="btn btn-success">
                                    Update
                                </button>
                                <button v-else type="submit" class="btn btn-success">
                                    Submit
                                </button>
                            </div>
                        </div>
                    </Form>
                </div>
            </div>
        </div>
    </div>
    <products-form-pictures></products-form-pictures>
</template>

<style scoped>
.checkbox-item {
    float: left;
}

.checkbox-label {
    font-weight: normal !important;
}

.checkbox-label input[type="checkbox"] {
    margin-right: 8px;
}
</style>
