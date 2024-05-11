<script setup>
import { useAuthUserStore } from '../stores/AuthUserStore';
import { useRouter } from 'vue-router';
import { useSettingStore } from '../stores/SettingStore';

const router = useRouter();
const authUserStore = useAuthUserStore();
const settingStore = useSettingStore();

const logout = () => {
    axios.post('/logout')
    .then((response) => {
        authUserStore.user.name = '';
        router.push('/login');
    });
};
</script>

<template>
    <aside class="main-sidebar sidebar-dark-primary elevation-4">

        <a href="#" class="brand-link">
            <img src="https://adminlte.io/themes/v3/dist/img/AdminLTELogo.png" alt="AdminLTE Logo"
                class="brand-image img-circle elevation-3" style="opacity: .8">
            <span class="brand-text font-weight-light">{{ settingStore.setting.app_name }}</span>
        </a>

        <div class="sidebar">

            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img :src="authUserStore.user.avatar" class="img-circle elevation-2" alt="User Image">
                </div>
                <div class="info">
                    <a href="#" class="d-block">{{ authUserStore.user.name }}</a>
                </div>
            </div>

            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <li class="nav-item">
                        <router-link to="/admin/dashboard" active-class="active" class="nav-link">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Dashboard
                            </p>
                        </router-link>
                    </li>

                    <li class="nav-item">
                        <router-link to="/admin/mediamanager"
                            :class="$route.path.startsWith('/admin/mediamanager') ? 'active' : ''" class="nav-link">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p>
                                Media Manager
                            </p>
                        </router-link>
                    </li>

                    
                    <li class="nav-item">
                        <router-link to="/admin/sliders"
                            :class="$route.path.startsWith('/admin/sliders') ? 'active' : ''" class="nav-link">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p>
                                Sliders
                            </p>
                        </router-link>
                    </li>

                    <li class="nav-item">
                        <router-link to="/admin/properties"
                            :class="$route.path.startsWith('/admin/properties') ? 'active' : ''" class="nav-link">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p>
                                Products
                            </p>
                        </router-link>
                    </li>

                    <li class="nav-item">
                        <router-link to="/admin/propertytypes"
                            :class="$route.path.startsWith('/admin/propertytypes') ? 'active' : ''" class="nav-link">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p>
                                Categories
                            </p>
                        </router-link>
                    </li>

                    <li class="nav-item">
                        <router-link to="/admin/amenities"
                            :class="$route.path.startsWith('/admin/amenities') ? 'active' : ''" class="nav-link">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p>
                                Colors
                            </p>
                        </router-link>
                    </li>

                    <li class="nav-item">
                        <router-link to="/admin/features"
                            :class="$route.path.startsWith('/admin/features') ? 'active' : ''" class="nav-link">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p>
                                Material
                            </p>
                        </router-link>
                    </li>

                    <li class="nav-item">
                        <router-link to="/admin/lineitems"
                            :class="$route.path.startsWith('/admin/lineitems') ? 'active' : ''" class="nav-link">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p>
                                Sizes
                            </p>
                        </router-link>
                    </li>

                    <li class="nav-item">
                        <router-link to="/admin/locations"
                            :class="$route.path.startsWith('/admin/locations') ? 'active' : ''" class="nav-link">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p>
                                ABC
                            </p>
                        </router-link>
                    </li>

                    <li class="nav-item">
                        <router-link to="/admin/services"
                            :class="$route.path.startsWith('/admin/services') ? 'active' : ''" class="nav-link">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p>
                                ABC
                            </p>
                        </router-link>
                    </li>

                    <li class="nav-item">
                        <router-link to="/admin/bookings"
                            :class="$route.path.startsWith('/admin/bookings') ? 'active' : ''" class="nav-link">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p>
                                Orders
                            </p>
                        </router-link>
                    </li>

                    <li class="nav-item">
                        <router-link to="/admin/appointments"
                            :class="$route.path.startsWith('/admin/appointments') ? 'active' : ''" class="nav-link">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p>
                                Inquiries
                            </p>
                        </router-link>
                    </li>

                    <li class="nav-item">
                        <router-link to="/admin/users" active-class="active" class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                Users
                            </p>
                        </router-link>
                    </li>

                    <li class="nav-item">
                        <router-link to="/admin/settings" active-class="active" class="nav-link">
                            <i class="nav-icon fas fa-cog"></i>
                            <p>
                                Settings
                            </p>
                        </router-link>
                    </li>

                    <li class="nav-item">
                        <router-link to="/admin/profile" active-class="active" class="nav-link">
                            <i class="nav-icon fas fa-user"></i>
                            <p>
                                Profile
                            </p>
                        </router-link>
                    </li>

                    <li class="nav-item">
                        <form class="nav-link">
                            <a href="#" @click.prevent="logout">
                                <i class="nav-icon fas fa-sign-out-alt"></i>
                                <p>
                                    Logout
                                </p>
                            </a>
                        </form>

                    </li>
                </ul>
            </nav>

        </div>

    </aside>
</template>
