<template>
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Attribute Values</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Attribute Values</li>
          </ol>
        </div>
      </div>
    </div>
  </div>
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-12">
          <!-- Add New Attribute Value Button -->
          <div class="d-flex justify-content-between mb-2">
            <div>
              <router-link to="/admin/attributevalues/create">
                <button class="btn btn-primary"><i class="fa fa-plus-circle mr-1"></i> Add New Attribute Value</button>
              </router-link>
            </div>
          </div>
          <!-- Attribute Values Table -->
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
                  <tr v-for="(attributevalue, index) in attributevalues" :key="attributevalue.id">
                    <td>{{ index + 1 }}</td>
                    <td>{{ attributevalue.attribute.name }} > {{ attributevalue.name }}</td>
                    <td>
                      <!-- Edit Attribute Value Link -->
                      <router-link :to="`/admin/attributevalues/${attributevalue.id}/edit`">
                        <i class="fa fa-edit mr-2"></i>
                      </router-link>
                      <!-- Delete Attribute Value Button -->
                      <a href="#" @click.prevent="deleteAttributeValue(attributevalue.id)">
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
const attributevalues = ref([]);

const fetchAttributeValues = () => {
  axios.get('/api/attributevalues')
    .then(response => {
      attributevalues.value = response.data;
      initSortable();
    })
    .catch(error => {
      console.error('Error fetching attribute values:', error);
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
  const movedAttributeValue = attributevalues.value.splice(evt.oldIndex, 1)[0];
  attributevalues.value.splice(evt.newIndex, 0, movedAttributeValue);

  // Extract IDs in the new order
  const idsInNewOrder = attributevalues.value.map(attribute => attribute.id);

  // Send the updated order to the backend
  axios.post('/api/attributevalues/update-sort-order', { ids: idsInNewOrder })
      .then(response => {
        console.log(response.data.message); // Log success message
      })
      .catch(error => {
        console.error('Error updating sort order:', error); // Log error
      });
};

const deleteAttributeValue = id => {
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
      axios.delete(`/api/attributevalues/${id}`)
        .then(() => {
          attributevalues.value = attributevalues.value.filter(attributevalue => attributevalue.id !== id);
          Swal.fire('Deleted!', 'Your attribute value has been deleted.', 'success');
        })
        .catch(error => {
          Swal.fire('Error!', 'Failed to delete attribute value.', 'error');
          console.error('Error deleting attribute value:', error);
        });
    }
  });
};

onMounted(() => {
  fetchAttributeValues();
});
</script>
