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
                  <button class="btn btn-primary"><i class="fa fa-plus-circle mr-1"></i> Add New Attribute</button>
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
                    <tr v-for="(attribute, index) in attributes" :key="attribute.id">
                      <td>{{ index + 1 }}</td>
                      <td>{{ attribute.name }}</td>
                      <td>
                        <!-- Edit Attribute Link -->
                        <router-link :to="`/admin/attributes/${attribute.id}/edit`">
                          <i class="fa fa-edit mr-2"></i>
                        </router-link>
                        <!-- Delete Attribute Button -->
                        <a href="#" @click.prevent="deleteAttribute(attribute.id)">
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
  import axios from 'axios';
  import Swal from 'sweetalert2';
  
  const sortableList = ref(null);
  const attributes = ref([]);
  
  const fetchAttributes = () => {
    axios.get('/api/attributes')
      .then(response => {
        attributes.value = response.data;
        initSortable();
      })
      .catch(error => {
        console.error('Error fetching attributes:', error);
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
    const movedAttribute = attributes.value.splice(evt.oldIndex, 1)[0];
    attributes.value.splice(evt.newIndex, 0, movedAttribute);

    // Extract IDs in the new order
    const idsInNewOrder = attributes.value.map(attribute => attribute.id);

    // Send the updated order to the backend
    axios.post('/api/attributes/update-sort-order', { ids: idsInNewOrder })
        .then(response => {
        console.log(response.data.message); // Log success message
        })
        .catch(error => {
        console.error('Error updating sort order:', error); // Log error
        });
    };

  const deleteAttribute = id => {
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
        axios.delete(`/api/attributes/${id}`)
          .then(() => {
            attributes.value = attributes.value.filter(attribute => attribute.id !== id);
            Swal.fire('Deleted!', 'Your attribute has been deleted.', 'success');
          })
          .catch(error => {
            Swal.fire('Error!', 'Failed to delete attribute.', 'error');
            console.error('Error deleting attribute:', error);
          });
      }
    });
  };
  
  onMounted(() => {
    fetchAttributes();
  });
  </script>
  