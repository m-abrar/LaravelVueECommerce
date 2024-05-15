<script setup>
import { onMounted, ref, computed } from 'vue';
import Swal from 'sweetalert2';
import axios from 'axios';
import Dropzone from '../components/Dropzone.vue';

const deleteImage = (mediaId) => {
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
            axios.delete(`/api/media/${mediaId}`)
                .then((response) => {
                    refreshFiles();
                    Swal.fire(
                        'Deleted!',
                        'Your file has been deleted.',
                        'success'
                    )
                });
        }
    })
};



const availableMedia = ref([]);

const refreshFiles = () => {
    axios.get('/api/media')
        .then((response) => {
            availableMedia.value = response.data;
        })
};
onMounted(() => {
    refreshFiles();
});
</script>

<template>
    <section class=" bg-light">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col d-flex justify-content-center align-items-center">
                    <div class="card border-0 " style="width:100%">
                        <div class="card-body">
                            <h3 class="mb-4">Upload File(s)</h3>
                            <hr />
                            <Dropzone paramName="image" @refresh-parent-files="refreshFiles" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-light">
        <div class="container py-1 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col d-flex justify-content-center align-items-center">

                    <div class="card border-0 " style="width:100%">
                        <div class="card-body">

                            <h3 class="mb-4">Manage Files</h3>
                            <hr />
                            <div class="col-lg-12">
                                <!-- Iterate through available images -->
                                <div
                                    class="image-container col-lg-2 col-md-3 col-sm-4 col-sm-6"
                                    v-for="media in availableMedia"
                                    :key="media.id"
                                    @click="openLightbox(media.url)"
                                >
                                    <div class="image-wrapper">
                                    <img :src="media.url" alt="Image" class="image" />
                                    </div>
                                    <button @click="deleteImage(media.id)" class="delete-button" title="Delete">
                                    X
                                    </button>
                                </div>
                                </div>
                                <v-img-lightbox ref="lightbox">
                                <!-- Lightbox content goes here -->
                                <img :src="lightboxImage" alt="Lightbox Image" />
                                </v-img-lightbox>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>


<style scoped>

.image-container {
  position: relative;
  display: inline-block;
  margin: 10px;
  min-height: 200px;
  max-width: 150px;
  cursor: pointer;
}

.image-wrapper {
  position: relative;
  width: 100%;
  padding-top: 100%; /* 1:1 aspect ratio (square) */
  overflow: hidden;
}

.image {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  object-fit: cover; /* Fill the box while maintaining aspect ratio */
  transition: transform 0.3s;
}

.image-container:hover .image {
  transform: scale(1.05); /* Scale up the image on hover */
}

.delete-button {
  position: absolute;
  top: 5px;
  right: 5px;
  background-color: red;
  color: white;
  border: none;
  border-radius: 50%;
  width: 25px;
  height: 25px;
  font-size: 16px;
  cursor: pointer;
  visibility: hidden;
  transition: visibility 0.3s, transform 0.3s;
}

.image-container:hover .delete-button {
  visibility: visible;
  transform: scale(1.2);
}
</style>