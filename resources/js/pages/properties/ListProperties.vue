<script setup>
import { onMounted, ref, computed } from 'vue';
import Swal from 'sweetalert2';
import axios from 'axios';

const selectedType = ref();
const propertyTypes = ref([]);
const propertiesList = ref([]);

const getPropertyTypes = () => {
    axios.get('/api/propertytypes/withcount')
        .then((response) => {
            propertyTypes.value = response.data;
        })
};
const getProperties = (type) => {
    selectedType.value = type;
    const params = {};
    if (type) {
        params.type = type;
    }
    axios.get('/api/properties', {
        params: params,
    })
        .then((response) => {
            propertiesList.value = response.data;
        })
};
const propertiesCount = computed(() => {
    return propertyTypes.value.map(status => status.count).reduce((acc, value) => acc + value, 0);
});

const updatePropertyTypesCount = (id) => {
    const deletedPropertyType = propertiesList.value.data.find(propertiesList => propertiesList.id === id).status.name;
    const statusToUpdate = propertyStatus.value.find(status => status.name === deletedPropertyStatus);
    statusToUpdate.count--;
};

const deleteProperty = (id) => {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            axios.delete(`/api/properties/${id}`)
                .then((response) => {
                    updatePropertyTypesCount(id);
                    propertiesList.value.data = propertiesList.value.data.filter(propertiesList => propertiesList.id !== id);
                    Swal.fire(
                        'Deleted!',
                        'Your file has been deleted.',
                        'success'
                    )
                });
        }
    })
};

onMounted(() => {
    getProperties();
    getPropertyTypes();
});
</script>
<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Products</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Products</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="d-flex justify-content-between mb-2">
                        <div>
                            <router-link to="/admin/properties/create">
                                <button class="btn btn-primary"><i class="fa fa-plus-circle mr-1"></i> Add New Product</button>
                            </router-link>
                        </div>
                        <div class="btn-group">
                            <button @click="getProperties()" type="button" class="btn"
                                :class="[typeof selectedType === 'undefined' ? 'btn-secondary' : 'btn-default']">
                                <span class="mr-1">All</span>
                                <span class="badge badge-pill badge-info">{{ propertiesCount }}</span>
                            </button>
                            <button v-for="type in propertyTypes" @click="getProperties(type.id)" type="button"
                                :class="[selectedType === type.id ? 'btn btn-secondary' : 'btn btn-default']">
                                <span class="mr-1">{{ type.name }}</span>
                                <span class="badge badge-pill" :class="`badge-${type.color}`">{{ type.count }}</span>
                            </button>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Type</th>
                                        <th scope="col">Code</th>
                                        <th scope="col">Options</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(property, index) in propertiesList.data" :key="property.id">
                                        <td>{{ index + 1 }}</td>
                                        <td>{{ property.name }}</td>
                                        <td>{{ property.type.name }}</td>
                                        <td>{{ property.item_code }}</td>
                                        <!-- <td>
                                            <span class="badge" :class="`badge-${properties.status.color}`">{{
                                                properties.status.name }}</span>
                                        </td> -->
                                        <td>
                                            <router-link :to="`/admin/properties/${property.id}/edit`">
                                                <i class="fa fa-edit mr-2"></i>
                                            </router-link>

                                            <a href="#" @click.prevent="deleteProperties(property.id)">
                                                <i class="fa fa-trash text-danger"></i>
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>  
</template>