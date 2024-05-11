<template>
  <div>
    <div class="wrapper" :style="{ visibility: wrapperVisibility, opacity: wrapperOpacity }">DROP HERE</div>
    <div class="dropzone" id="dropzone">
      <div class="dz-message">
        <span class="text">Drop files here or click to upload +</span>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      wrapperVisibility: "hidden",
      wrapperOpacity: 0,
      lastTarget: null,
    };
  },

  methods: {
    hideWrapper() {
      this.wrapperVisibility = "hidden";
      this.wrapperOpacity = 0;
    },
    showWrapper() {
      this.wrapperVisibility = "";
      this.wrapperOpacity = 0.5;
    },

    refreshParentFiles(files) {
      this.$emit('refresh-parent-files');
    },

  },
  mounted() {
    const vm = this;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const myDropzone = new Dropzone(".dropzone", {
      url: "http://127.0.0.1:8000/media-upload",
      headers: {
        'X-CSRF-TOKEN': csrfToken,
      },
      init: function () {
        
        this.on("success", (file, response) => {
            vm.refreshParentFiles();
        });

        this.on("error", function (file, errorMessage) {

          // Customize the error message
          let customErrorMessage = errorMessage || "An error occurred while uploading the file";

          // Ensure that errorMessage is a string
          if (typeof customErrorMessage === 'object') {
            customErrorMessage = customErrorMessage.errors.file;
          }
          // Set the error message for the file's preview element
          file.previewElement.querySelector(".dz-error-message span").textContent = customErrorMessage;

        });
      },

    });

    window.addEventListener("dragenter", (e) => {
      this.showWrapper();
      this.lastTarget = e.target;
    });

    window.addEventListener("dragleave", (e) => {
      if (e.target === this.lastTarget || e.target === document) {
        this.hideWrapper();
      }
    });

    window.addEventListener("dragover", (e) => {
      e.preventDefault();
    });

    window.addEventListener("drop", (e) => {
      e.preventDefault();
      this.hideWrapper();
      myDropzone.handleFiles(e.dataTransfer.files);
    });
  },
};
</script>

<style scoped>
.wrapper {
  background: #39E2B6;
  height: 100%;
  width: 100%;
  position: fixed;
  top: 0;
  z-index: 9999;
  text-align: center;
  left: 0;
  font-size: 100px;
  font-family: calibri;
  color: white;
  line-height: 100vh;
}

.dropzone {
  width: 98%;
  margin: 1%;
  border: 2px dashed #3498db !important;
  border-radius: 5px;
}
</style>
