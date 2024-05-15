<template>
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card card-primary card-outline">
            <div class="card-header">
              <h3 class="card-title">Pictures</h3>
              <div v-if="mediaFiles.length > 0" class="card-tools">
                <button type="button" class="btn btn-info float-right" @click="manageAttachments()">
                  <i class="fa fa-camera"></i> Add / Remove
                </button>
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body box-profile ">
              <div class="col-md-12">
                <div class="row" v-if="mediaFiles.length == 0">
                  <div class="text-center">
                    <button type="button" class="btn btn-lg btn-warning float-right" @click="manageAttachments()">
                      <i class="fa fa-camera"></i> Add / Remove
                    </button>
                  </div>
                </div>

                <div class="row">
                  <div style="min-width: 200px; display: flex; float:left" v-for="(attachment, index) in mediaFiles">
                    <span style="float:left">#{{ attachment.order }}<br /><br /></span>

                    <span style="float:left">
                      <button class="btn btn-sm"
                        :class="featuredMediaFile && attachment.id === featuredMediaFile.id ? 'btn-success' : 'btn-default'"
                        @click="featureAttachment(attachment.id)">
                        <i class="fa fa-star"></i>
                      </button>
                    </span>

                    <span style="float:left">
                      <button class="btn btn-sm btn-default" @click="addRemoveAttachment(attachment.id)">
                        <i class="fa fa-times"></i>
                      </button>
                    </span>
                    <span style="float:right">
                      <img :src="attachment.url" class="my-handle thumbnail" :alt="attachment.name" />
                    </span>
                  </div>
                </div>

              </div>
              <!-- Attached Files -->

            </div>
            <!-- card-body -->
            <div class="card-footer" v-if="mediaFiles.length > 0">
              <p>Featured image is marked by green star. Order can be changed by dragging an image to desired position</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


  <!-- Modal Attachments -->
  <div class="modal fade" id="manageAttachments" tabindex="-1" role="dialog" aria-labelledby="manageAttachmentsLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <ul class="nav nav-pills">
            <li class="nav-item"><a class="nav-link" href="#availableimages" data-toggle="tab">Available Images</a>
            </li>
            <li class="nav-item"><a class="nav-link active show" href="#localdrive" data-toggle="tab">Add New</a>
            </li>
          </ul>
        </div>
        <div class="modal-body">
          <div class="tab-content">
            <!-- Settings Tab -->
            <div class="tab-pane availableimages" id="availableimages">
              <div class="row">
                <div class="col-md-4 availableimage" v-for="availableimage in availableMediaFiles">
                  <img :src="availableimage.url" class="thumbnail" :alt="availableimage.name" />
                  <button class="btn btn-xs" :class="inArray(availableimage.id) ? 'btn-success' : 'btn-default'"
                    @click="addRemoveAttachment(availableimage.id)">
                    <i class="fa fa-check"></i>
                  </button>
                </div>
              </div>
            </div>
            <div class="tab-pane active show" id="localdrive">
              <Dropzone paramName="image" @refresh-parent-files="refreshFiles" />
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success" data-dismiss="modal">DONE</button>
        </div>
      </div>
    </div>
  </div>
</template>
  
<script>
import { onMounted, ref } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { useToastr } from '@/toastr';
import Dropzone from '@/components/Dropzone.vue';

import axios from 'axios';
import Draggable from 'vuedraggable';
export default {
  components: {
    Draggable, Dropzone
  },
  setup() {
    const route = useRoute();
    const router = useRouter();
    const toastr = useToastr();

    const addRemoveAttachment = (attachment_id) => {

      var feature_image_url = `/api/category/${route.params.id}/media/add-remove/${attachment_id}`;
      axios.get(feature_image_url)
        .then((response) => {
          toastr.success('Image has been ' + JSON.stringify(response.data));
          getMediaFiles();
          getAvailableMediaFiles();
        })
        .catch((error) => {
          console.error('Error updating image:', error);
        });

    };


    const availableMediaFiles = ref();

    const getAvailableMediaFiles = () => {
      axios.get('/api/media')
        .then((response) => {
          availableMediaFiles.value = response.data;
        })
        .catch((error) => {
          console.error('Error fetching available media files:', error);
        });
    };

    const refreshFiles = () => {
      getAvailableMediaFiles();
      getMediaFiles();

    };
    const manageAttachments = () => {
      $('#manageAttachments').modal('show');
    };

    const mediaFiles = ref([]);
    const attachmentIDs = ref([]);
    const featuredMediaFile = ref([]);

    const getMediaFiles = () => {
      axios.get(`/api/category/${route.params.id}/media/all`)
        .then((response) => {
          featuredMediaFile.value = response.data.featuredMediaFile;
          mediaFiles.value = response.data.mediaFiles;
          attachmentIDs.value = response.data.attachmentIDs;
        })
        .catch((error) => {
          console.error('Error fetching product images:', error);
        });
    };

    const updateOrder = () => {
      this.attachments.map((attachment, index) => {
        attachment.order = index + 1;
      })
      axios.put('/api/category/images-order-update', {
        attachments: this.attachments
      }).then((response) => {
        console.log(response);
      })
    };

    const featureAttachment = (attachment_id) => {

      var feature_image_url = `/api/category/${route.params.id}/media/featured-update/${attachment_id}`;

      axios.get(feature_image_url)
        .then((response) => {
          toastr.success('Featured image has been updated');
          getMediaFiles();
        })
        .catch((error) => {
          console.error('Error updating featured image:', error);
        });

    };

    const inArray = (value) => {
      return attachmentIDs.value.includes(value);
    };

    onMounted(() => {
      getAvailableMediaFiles();
      getMediaFiles();
    });

    return {
      availableMediaFiles,
      mediaFiles,
      featuredMediaFile,
      updateOrder,
      featureAttachment,
      manageAttachments,
      getAvailableMediaFiles,
      inArray,
      addRemoveAttachment,
      refreshFiles,
    };
  },
};
</script>

<style scoped>
.thumbnail {
  width: 114px;
  max-height: 114px;
}
</style>