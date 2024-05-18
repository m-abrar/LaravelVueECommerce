<script setup>
import { onMounted, ref } from "vue";
import axios from "axios";
import { useToastr } from "@/toastr";

const settings = ref({});
const errors = ref({});
const toastr = useToastr();

const getSettings = () => {
    axios.get("/api/settings").then((response) => {
        settings.value = response.data;
    });
};

const updateSettings = () => {
    errors.value = {};
    axios
        .post("/api/settings", settings.value)
        .then(() => {
            toastr.success("Settings updated successfully!");
        })
        .catch((error) => {
            if (error.response && error.response.status === 422) {
                errors.value = error.response.data.errors;
            }
        });
};

const handleFileChange = (event, key) => {
    const file = event.target.files[0];
    if (file) {
        const formData = new FormData();
        formData.append(key, file);

        // Append other settings to formData if needed
        for (const [key, value] of Object.entries(settings.value)) {
            if (key !== 'logo' && key !== 'favicon') { // exclude file fields if already appended
                formData.append(key, value);
            }
        }

        axios.post("/api/settings", formData)
            .then((response) => {
                settings.value[key] = response.data[key];
                toastr.success("File uploaded successfully!");
            })
            .catch((error) => {
                if (error.response && error.response.status === 422) {
                    errors.value = error.response.data.errors;
                }
            });
    }
};

onMounted(() => {
    getSettings();
});
</script>

<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Settings</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Settings</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-3">
                    <ul class="nav flex-column nav-pills" id="myTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="general-tab" data-toggle="tab" href="#general" role="tab"
                                aria-controls="general" aria-selected="true">General</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="logo-tab" data-toggle="tab" href="#logo" role="tab"
                                aria-controls="logo" aria-selected="true">Logo/Favicon</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="seo-tab" data-toggle="tab" href="#seo" role="tab"
                                aria-controls="seo" aria-selected="true">SEO</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab"
                                aria-controls="contact" aria-selected="false">Contact</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="social-tab" data-toggle="tab" href="#social" role="tab"
                                aria-controls="social" aria-selected="false">Social & Other</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="smtp-tab" data-toggle="tab" href="#smtp" role="tab"
                                aria-controls="smtp" aria-selected="false">SMTP</a>
                        </li>
                    </ul>
                </div>
                <div class="col-9">
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="general" role="tabpanel"
                            aria-labelledby="general-tab">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">General Settings</h3>
                                </div>
                                <form @submit.prevent="updateSettings">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="appName">App Display Name</label>
                                            <input v-model="settings.app_name" type="text" class="form-control"
                                                id="appName" placeholder="Enter app display name" />
                                            <span class="text-danger text-sm" v-if="errors.app_name">{{ errors.app_name[0] }}</span>
                                        </div>
                                        <div class="form-group">
                                            <label for="dateFormat">Date Format</label>
                                            <select v-model="settings.date_format" class="form-control">
                                                <option value="m/d/Y">MM/DD/YYYY</option>
                                                <option value="d/m/Y">DD/MM/YYYY</option>
                                                <option value="Y-m-d">YYYY-MM-DD</option>
                                                <option value="F j, Y">Month DD, YYYY</option>
                                                <option value="j F Y">DD Month YYYY</option>
                                            </select>
                                            <span class="text-danger text-sm" v-if="errors.date_format">{{ errors.date_format[0] }}</span>
                                        </div>
                                        <div class="form-group">
                                            <label for="websiteMode">Website Mode</label>
                                            <select v-model="settings.website_mode" class="form-control">
                                                <option value="live">Live</option>
                                                <option value="maintenance">Maintenance</option>
                                            </select>
                                            <span class="text-danger text-sm" v-if="errors.website_mode">{{ errors.website_mode[0] }}</span>
                                        </div>
                                        <div class="form-group">
                                            <label for="paginationLimit">Pagination Limit</label>
                                            <input v-model="settings.pagination_limit" type="number" class="form-control"
                                                id="paginationLimit" placeholder="Enter pagination limit" />
                                            <span class="text-danger text-sm" v-if="errors.pagination_limit">{{ errors.pagination_limit[0] }}</span>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-save mr-1"></i>Save Changes
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="logo" role="tabpanel" aria-labelledby="logo-tab">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Logo & Favicon</h3>
                                </div>
                                <form @submit.prevent="updateSettings">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="logo">Logo</label>
                                            <input @change="event => handleFileChange(event, 'logo')" type="file" class="form-control" id="logo" />
                                            <span class="text-danger text-sm" v-if="errors.logo">{{ errors.logo[0] }}</span>
                                        </div>
                                        <div class="form-group">
                                            <label for="favicon">Favicon</label>
                                            <input @change="event => handleFileChange(event, 'favicon')" type="file" class="form-control" id="favicon" />
                                            <span class="text-danger text-sm" v-if="errors.favicon">{{ errors.favicon[0] }}</span>
                                        </div>
                                        <div class="form-group">
                                            <label for="copyright">Copyright</label>
                                            <input v-model="settings.copyright" type="text" class="form-control" id="copyright" placeholder="Enter copyright" />
                                        </div>
                                        <div class="form-group">
                                            <label for="designedBy">Designed By</label>
                                            <input v-model="settings.designedBy" type="text" class="form-control" id="designedBy" placeholder="Enter designer name" />
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-save mr-1"></i>Save Changes
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="seo" role="tabpanel" aria-labelledby="seo-tab">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">SEO / Meta Settings</h3>
                                </div>
                                <form @submit.prevent="updateSettings">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="keywords">Keywords</label>
                                            <input v-model="settings.keywords" type="text" class="form-control" id="keywords"
                                                placeholder="Enter keywords" />
                                            <span class="text-danger text-sm" v-if="errors.keywords">{{ errors.keywords[0] }}</span>
                                        </div>
                                        <div class="form-group">
                                            <label for="description">Meta Description</label>
                                            <input v-model="settings.meta_description" type="text" class="form-control"
                                                id="description" placeholder="Enter meta description" />
                                            <span class="text-danger text-sm" v-if="errors.meta_description">{{ errors.meta_description[0] }}</span>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-save mr-1"></i>Save Changes
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Contact Information</h3>
                                </div>
                                <form @submit.prevent="updateSettings">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="businessName">Business Name</label>
                                            <input v-model="settings.businessName" type="text" class="form-control"
                                                id="businessName" placeholder="Enter business name" />
                                        </div>
                                        <div class="form-group">
                                            <label for="businessAddress">Business Address</label>
                                            <input v-model="settings.businessAddress" type="text" class="form-control"
                                                id="businessAddress" placeholder="Enter business address" />
                                        </div>
                                        <div class="form-group">
                                            <label for="ownerName">Owner Name</label>
                                            <input v-model="settings.ownerName" type="text" class="form-control"
                                                id="ownerName" placeholder="Enter owner name" />
                                        </div>
                                        <div class="form-group">
                                            <label for="phone">Phone</label>
                                            <input v-model="settings.phone" type="text" class="form-control" id="phone"
                                                placeholder="Enter phone number" />
                                        </div>
                                        <div class="form-group">
                                            <label for="phone2">Phone 2</label>
                                            <input v-model="settings.phone2" type="text" class="form-control"
                                                id="phone2" placeholder="Enter second phone number" />
                                        </div>
                                        <div class="form-group">
                                            <label for="whatsapp">WhatsApp</label>
                                            <input v-model="settings.whatsapp" type="text" class="form-control"
                                                id="whatsapp" placeholder="Enter WhatsApp number" />
                                        </div>
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input v-model="settings.email" type="email" class="form-control" id="email"
                                                placeholder="Enter email" />
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-save mr-1"></i>Save Changes
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="social" role="tabpanel" aria-labelledby="social-tab">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Social Links & More</h3>
                                </div>
                                <form @submit.prevent="updateSettings">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="googleMap">Google Map</label>
                                            <input v-model="settings.googleMap" type="text" class="form-control"
                                                id="googleMap" placeholder="Enter Google Map URL" />
                                        </div>
                                        <div class="form-group">
                                            <label for="facebookURL">Facebook URL</label>
                                            <input v-model="settings.facebookURL" type="text" class="form-control"
                                                id="facebookURL" placeholder="Enter Facebook URL" />
                                        </div>
                                        <div class="form-group">
                                            <label for="instagramURL">Instagram URL</label>
                                            <input v-model="settings.instagramURL" type="text" class="form-control"
                                                id="instagramURL" placeholder="Enter Instagram URL" />
                                        </div>
                                        <div class="form-group">
                                            <label for="youtubeURL">YouTube URL</label>
                                            <input v-model="settings.youtubeURL" type="text" class="form-control"
                                                id="youtubeURL" placeholder="Enter YouTube URL" />
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-save mr-1"></i>Save Changes
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="smtp" role="tabpanel" aria-labelledby="smtp-tab">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">SMTP Settings</h3>
                                </div>
                                <form @submit.prevent="updateSettings">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="smtpHost">SMTP Host</label>
                                            <input v-model="settings.host" type="text" class="form-control"
                                                id="smtpHost" placeholder="Enter SMTP host">
                                        </div>
                                        <div class="form-group">
                                            <label for="smtpPort">SMTP Port</label>
                                            <input v-model="settings.port" type="number" class="form-control"
                                                id="smtpPort" placeholder="Enter SMTP port">
                                        </div>
                                        <div class="form-group">
                                            <label for="smtpUser">SMTP Username</label>
                                            <input v-model="settings.username" type="text" class="form-control"
                                                id="smtpUser" placeholder="Enter SMTP username">
                                        </div>
                                        <div class="form-group">
                                            <label for="smtpPassword">SMTP Password</label>
                                            <input v-model="settings.password" type="password" class="form-control"
                                                id="smtpPassword" placeholder="Enter SMTP password">
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-save mr-1"></i>Save Changes
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
