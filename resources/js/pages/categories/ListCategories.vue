<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Categories</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Categories</li>
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
                <router-link to="/admin/categories/create">
                  <button class="btn btn-primary"><i class="fa fa-plus-circle mr-1"></i> Add New Category</button>
                </router-link>
              </div>
            </div>
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
                    <tr v-for="(category, index) in categories" :key="category.id">
                      <td>{{ index + 1 }}</td>
                      <td>{{ category.name }}</td>
                      <td>
                        <router-link :to="`/admin/categories/${category.id}/edit`">
                          <i class="fa fa-edit mr-2"></i>
                        </router-link>
                        <a href="#" @click.prevent="deleteCategory(category.id)">
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
  
  <script setup>
  import { ref, onMounted } from 'vue';
  import Sortable from 'sortablejs';
  import Swal from 'sweetalert2';
  import axios from 'axios';
  
  const sortableList = ref(null);
  const categories = ref([]);
  
  const getCategories = () => {
    axios.get('/api/categories')
      .then(response => {
        categories.value = response.data;
        initSortable();
      })
      .catch(error => {
        console.error('Error fetching categories:', error);
      });
  };
  
  const initSortable = () => {
    if (sortableList.value) {
      new Sortable(sortableList.value, {
        animation: 150,
        ghostClass: 'sortable-ghost',
        onEnd: handleSortEnd,
      });
    }
  };
  
  const handleSortEnd = evt => {
    // Reorder the categories
    const movedCategory = categories.value.splice(evt.oldIndex, 1)[0];
    categories.value.splice(evt.newIndex, 0, movedCategory);
  
    // Extract IDs in the new order
    const idsInNewOrder = categories.value.map(category => category.id);
  
    // Send the updated order to the backend
    axios.post('/api/categories/update-sort-order', { ids: idsInNewOrder })
      .then(response => {
        console.log(response.data.message); // Log success message
      })
      .catch(error => {
        console.error('Error updating sort order:', error); // Log error
      });
  };
  
  const deleteCategory = id => {
    Swal.fire({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!'
    }).then(result => {
      if (result.isConfirmed) {
        axios.delete(`/api/categories/${id}`)
          .then(() => {
            categories.value = categories.value.filter(category => category.id !== id);
            Swal.fire('Deleted!', 'Your category has been deleted.', 'success');
          })
          .catch(error => {
            Swal.fire('Error!', 'Failed to delete category.', 'error');
            console.error('Error deleting category:', error);
          });
      }
    });
  };
  
  onMounted(() => {
    getCategories();
  });
  </script>
  