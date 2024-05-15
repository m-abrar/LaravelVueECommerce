<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Attributes</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Attributes</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <!-- Add New Attribute Button -->
                    <div class="d-flex justify-content-between mb-2">
                        <div>
                            <router-link to="/admin/attributes/create">
                                <button class="btn btn-primary"><i class="fa fa-plus-circle mr-1"></i> Add New
                                    Attribute</button>
                            </router-link>
                        </div>
                    </div>
                    <!-- Attributes Table -->
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Options</th>
                                    </tr>
                                </thead>
                                <tbody ref="sortableList">
                                    <tr
                                        v-for="(attribute, index) in attributes"
                                        :key="attribute.id"
                                    >
                                        <td>{{ index + 1 }}</td>
                                        <td>{{ attribute.name }}</td>
                                        <td>
                                            <!-- Edit Attribute Link -->
                                            <router-link
                                                :to="`/admin/attributes/${attribute.id}/edit`"
                                            >
                                                <i class="fa fa-edit mr-2"></i>
                                            </router-link>
                                            <!-- Delete Attribute Button -->
                                            <a
                                                href="#"
                                                @click.prevent="
                                                    deleteAttribute(attribute.id)
                                                "
                                            >
                                                <i
                                                    class="fa fa-trash text-danger"
                                                ></i>
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

<script>
import Sortable from "sortablejs";
import axios from "axios"; // Import Axios for making HTTP requests
import Swal from 'sweetalert2';

export default {
    data() {
        return {
            attributes: [], // Initialize attributes array
        };
    },
    mounted() {
        // Fetch attributes data from API
        axios
            .get("/api/attributes")
            .then((response) => {
                this.attributes = response.data; // Populate attributes array with data from API
                // Initialize Sortable for attributes list
                const list = this.$refs.sortableList;
                if (list) {
                    new Sortable(list, {
                        animation: 150,
                        ghostClass: "sortable-ghost",
                        onEnd: this.handleSortEnd,
                    });
                }
            })
            .catch((error) => {
                console.error("Error fetching attributes:", error);
            });
    },
    methods: {
        handleSortEnd(evt) {
            const movedAttribute = this.attributes.splice(evt.oldIndex, 1)[0];
            this.attributes.splice(evt.newIndex, 0, movedAttribute);
        },
        deleteAttribute(id) {
    // Show confirmation dialog using SweetAlert
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
            // Send delete request to the server
            axios.delete(`/api/attributes/${id}`)
                .then(() => {
                    // Remove the deleted attribute from the attributes list
                    this.attributes = this.attributes.filter(attribute => attribute.id !== id);
                    // Show success message using SweetAlert
                    Swal.fire(
                        'Deleted!',
                        'Your attribute has been deleted.',
                        'success'
                    );
                })
                .catch((error) => {
                    // Show error message using SweetAlert
                    Swal.fire(
                        'Error!',
                        'Failed to delete attribute.',
                        'error'
                    );
                    console.error('Error deleting attribute:', error);
                });
        }
    });
},

    },
};
</script>
