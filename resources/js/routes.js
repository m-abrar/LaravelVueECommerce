import Dashboard from './components/Dashboard.vue';
import Dropzone from './components/Dropzone.vue';
import MediaManager from './components/MediaManager.vue';
import UploadFiles from './components/UploadFiles.vue'; //will be removed soon
import ListSliders from './pages/sliders/ListSliders.vue';
import SlidersForm from './pages/sliders/SlidersForm.vue';
import ListAppointments from './pages/appointments/ListAppointments.vue';
import AppointmentForm from './pages/appointments/AppointmentForm.vue';
import ListBookings from './pages/bookings/ListBookings.vue';
import BookingForm from './pages/bookings/BookingForm.vue';
import ListProducts from './pages/products/ListProducts.vue';
import ProductsForm from './pages/products/ProductsForm.vue';
import ListCategories from './pages/categories/ListCategories.vue';
import CategoryForm from './pages/categories/Categoryform.vue';
import ListAmenities from './pages/amenities/ListAmenities.vue';
import ListAttributes from './pages/attributes/ListAttributes.vue';
import AmenitiesForm from './pages/amenities/AmenitiesForm.vue';
import AttributesForm from './pages/attributes/AttributesForm.vue';
import ListFeatures from './pages/features/ListFeatures.vue';
import FeaturesForm from './pages/features/FeaturesForm.vue';
import ListServices from './pages/services/ListServices.vue';
import ServicesForm from './pages/services/ServicesForm.vue';
import ListLocations from './pages/locations/ListLocations.vue';
import LocationsForm from './pages/locations/LocationsForm.vue';
import ListLineItems from './pages/lineitems/ListLineItems.vue';
import LineItemsForm from './pages/lineitems/LineItemsForm.vue';
import UserList from './pages/users/UserList.vue';
import UpdateSetting from './pages/settings/UpdateSetting.vue';
import UpdateProfile from './pages/profile/UpdateProfile.vue';
import Login from './pages/auth/Login.vue';

export default [
    {
        path: '/login',
        name: 'admin.login',
        component: Login,
    },

    {
        path: '/admin/dashboard',
        name: 'admin.dashboard',
        component: Dashboard,
    },

    {
        path: '/admin/dropzone',
        name: 'admin.dropzone',
        component: Dropzone,
    },    
    
    {
        path: '/admin/mediamanager',
        name: 'admin.mediamanager',
        component: MediaManager,
    },

        
    {
        path: '/admin/uploadfiles',
        name: 'admin.uploadfiles',
        component: UploadFiles,
    },


    {
        path: '/admin/appointments',
        name: 'admin.appointments',
        component: ListAppointments,
    },

    {
        path: '/admin/appointments/create',
        name: 'admin.appointments.create',
        component: AppointmentForm,
    },

    {
        path: '/admin/appointments/:id/edit',
        name: 'admin.appointments.edit',
        component: AppointmentForm,
    },
        

    
    {
        path: '/admin/bookings',
        name: 'admin.bookings',
        component: ListBookings,
    },

    {
        path: '/admin/booking/create',
        name: 'admin.booking.create',
        component: BookingForm,
    },

    {
        path: '/admin/booking/:id/edit',
        name: 'admin.booking.edit',
        component: BookingForm,
    },
        




    {
        path: '/admin/sliders',
        name: 'admin.sliders',
        component: ListSliders,
    },

    {
        path: '/admin/sliders/create',
        name: 'admin.sliders.create',
        component: SlidersForm,
    },

    {
        path: '/admin/sliders/:id/edit',
        name: 'admin.sliders.edit',
        component: SlidersForm,
    },
        




    {
        path: '/admin/products',
        name: 'admin.products',
        component: ListProducts,
    },

    {
        path: '/admin/products/create',
        name: 'admin.products.create',
        component: ProductsForm,
    },

    {
        path: '/admin/products/:id/edit',
        name: 'admin.products.edit',
        component: ProductsForm,
    },

    {
        path: '/admin/categories/create',
        name: 'admin.categories.create',
        component: CategoryForm,
    },

    {
        path: '/admin/categories/:id/edit',
        name: 'admin.categories.edit',
        component: CategoryForm,
    },

    {
        path: '/admin/categories',
        name: 'admin.categories',
        component: ListCategories,
    },


    {
        path: '/admin/attributes',
        name: 'admin.attributes',
        component: ListAttributes,
    },

    {
        path: '/admin/attributes/create',
        name: 'admin.attributes.create',
        component: AttributesForm,
    },

    {
        path: '/admin/attributes/:id/edit',
        name: 'admin.attributes.edit',
        component: AttributesForm,
    },


    {
        path: '/admin/features',
        name: 'admin.features',
        component: ListFeatures,
    },

    {
        path: '/admin/features/create',
        name: 'admin.features.create',
        component: FeaturesForm,
    },

    {
        path: '/admin/features/:id/edit',
        name: 'admin.features.edit',
        component: FeaturesForm,
    },


    {
        path: '/admin/services',
        name: 'admin.services',
        component: ListServices,
    },
    
    {
        path: '/admin/services/create',
        name: 'admin.services.create',
        component: ServicesForm,
    },
    
    {
        path: '/admin/services/:id/edit',
        name: 'admin.services.edit',
        component: ServicesForm,
    },

    
    {
        path: '/admin/lineitems',
        name: 'admin.lineitems',
        component: ListLineItems,
    },
    
    {
        path: '/admin/lineitems/create',
        name: 'admin.lineitems.create',
        component: LineItemsForm,
    },
    
    {
        path: '/admin/lineitems/:id/edit',
        name: 'admin.lineitems.edit',
        component: LineItemsForm,
    },


    
    {
        path: '/admin/locations',
        name: 'admin.locations',
        component: ListLocations,
    },
    
    {
        path: '/admin/locations/create',
        name: 'admin.locations.create',
        component: LocationsForm,
    },
    
    {
        path: '/admin/locations/:id/edit',
        name: 'admin.locations.edit',
        component: LocationsForm,
    },

    
    {
        path: '/admin/users',
        name: 'admin.users',
        component: UserList,
    },

    {
        path: '/admin/settings',
        name: 'admin.settings',
        component: UpdateSetting,
    },

    {
        path: '/admin/profile',
        name: 'admin.profile',
        component: UpdateProfile,
    }
]
