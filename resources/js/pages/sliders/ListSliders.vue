<script setup>
import { onMounted, ref } from 'vue';
import Sortable from 'sortablejs';
import Swal from 'sweetalert2';
import axios from 'axios';

const sortableList = ref(null);
const sliders = ref([]);

const getSliders = () => {
  axios.get('/api/sliders')
    .then((response) => {
      sliders.value = response.data;
      initSortable();
    })
    .catch((error) => {
      console.error('Error fetching sliders:', error);
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

const handleSortEnd = (evt) => {
  const movedSlider = sliders.value.splice(evt.oldIndex, 1)[0];
  sliders.value.splice(evt.newIndex, 0, movedSlider);

  // Extract IDs in the new order
  const idsInNewOrder = sliders.value.map((slider) => slider.id);

  // Send the updated order to the backend
  axios.post('/api/sliders/update-sort-order', { ids: idsInNewOrder })
    .then((response) => {
      console.log(response.data.message); // Log success message
    })
    .catch((error) => {
      console.error('Error updating sort order:', error); // Log error
    });
};

const deleteSlider = (id) => {
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
      axios.delete(`/api/sliders/${id}`)
        .then(() => {
          sliders.value = sliders.value.filter((slider) => slider.id !== id);
          Swal.fire('Deleted!', 'Your slider has been deleted.', 'success');
        })
        .catch((error) => {
          Swal.fire('Error!', 'Failed to delete slider.', 'error');
          console.error('Error deleting slider:', error);
        });
    }
  });
};

onMounted(() => {
  getSliders();
});
</script>
<template>
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Sliders</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Sliders</li>
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
              <router-link to="/admin/sliders/create">
                <button class="btn btn-primary"><i class="fa fa-plus-circle mr-1"></i> Add New Slider</button>
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
                  <tr v-for="(slider, index) in sliders" :key="slider.id">
                    <td>{{ index + 1 }}</td>
                    <td>{{ slider.name }}</td>
                    <td>
                      <router-link :to="`/admin/sliders/${slider.id}/edit`">
                        <i class="fa fa-edit mr-2"></i>
                      </router-link>
                      <a href="#" @click.prevent="deleteSlider(slider.id)">
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