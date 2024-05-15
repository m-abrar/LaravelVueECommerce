<script setup>
import axios from 'axios';
import { reactive, onMounted, ref } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { useToastr } from '@/toastr';
import { Form } from 'vee-validate';
import flatpickr from "flatpickr";
import 'flatpickr/dist/themes/light.css';


const addNewLineItemForm = () => {
    var count = form.lineitems.length + 1;
    form.lineitems.push({ title: '', value: '', value_type: 'fixed', apply_on: '', is_required: '', display_order: count, image: 'placeholder.png' })
};
const deleteLineItemForm = (index) => {
    form.lineitems.splice(index, 1)
};

const addNewPriceForm = () => {
    var count = form.prices.length + 1;
    form.prices.push({ name: '', price: '', display_order: count, date_start: '', date_end: '', image: 'placeholder.png' })
};
const deletePriceForm = (index) => {
    form.prices.splice(index, 1)
};

const addNewServiceForm = () => {
    var count = form.services.length + 1;
    form.services.push({ title: '', price: '', display_order: count, description: '', image: 'placeholder.png' })
};
const deleteServiceForm = (index) => {
    form.services.splice(index, 1)
};


const addNewNeighbourForm = () => {
    var count = form.neighbours.length + 1;
    form.neighbours.push({ name: '', distance: '', display_order: count, description: '', icon: '', image: 'placeholder.png' })
};
const deleteNeighbourForm = (index) => {
    form.neighbours.splice(index, 1)
};

const addNewRoomForm = () => {
    var count = form.rooms.length + 1;
    form.rooms.push({ name: '', type: '', display_order: count, description: '', icon: '', image: 'placeholder.png' })
};
const deleteRoomForm = (index) => {
    form.rooms.splice(index, 1)
};


const router = useRouter();
const route = useRoute();
const toastr = useToastr();
const form = reactive({
    firstname: '',
    lastname: '',
    uniqid: '',
    user_id: null,
    adults: null,
    children: null,
    pets: '',
    phone: '',
    email: '',
    customer_profile_id: '',
    address_line_1: '',
    address_line_2: '',
    city: '',
    state: '',
    zip: '',
    country: '',
    product_id: null,
    lodging_amount: null,
    sub_total_detail: '',
    total_amount: null,
    status: '',
    date_start: null,
    date_end: null,
    created_by: '',
    housekeeper_id: null,
    notes: '',
    product: [],
    lineitems: [],
    services: [],
    payments: [],
    created_at: '',
});


const handleSubmit = (values, actions) => {
    if (editMode.value) {
        editBooking(values, actions);
    } else {
        createBooking(values, actions);
    }
};

const createBooking = (values, actions) => {
    axios.post('/api/booking/create', form)
        .then((response) => {
            router.push('/admin/bookings');
            toastr.success('Booking created successfully!');
        })
        .catch((error) => {
            actions.setErrors(error.response.data.errors);
        })
};

const editBooking = (values, actions) => {
    axios.put(`/api/booking/${route.params.id}/edit`, form)
        .then((response) => {
            router.push('/admin/bookings');
            toastr.success('Booking updated successfully!');
        })
        .catch((error) => {
            actions.setErrors(error.response.data.errors);
        })
};

const getBooking = () => {
    axios.get(`/api/booking/${route.params.id}/edit`)
        .then(({ data }) => {
            form.firstname = data.firstname;
            form.lastname = data.lastname;
            form.uniqid = data.uniqid;
            form.user_id = data.user_id;
            form.adults = data.adults;
            form.children = data.children;
            form.pets = data.pets;
            form.phone = data.phone;
            form.email = data.email;
            form.customer_profile_id = data.customer_profile_id;
            form.address_line_1 = data.address_line_1;
            form.address_line_2 = data.address_line_2;
            form.city = data.city;
            form.state = data.state;
            form.zip = data.zip;
            form.country = data.country;
            form.product_id = data.product_id;
            form.lodging_amount = data.lodging_amount;
            form.sub_total_detail = data.sub_total_detail;
            form.total_amount = data.total_amount;
            form.status = data.status;
            form.date_start = data.date_start;
            form.date_end = data.date_end;
            form.created_by = data.created_by;
            form.housekeeper_id = data.housekeeper_id;
            form.notes = data.notes;
            form.product = data.product;
            form.lineitems = data.lineitems;
            form.services = data.services;
            form.payments = data.payments;
            form.created_at = data.created_at;

        })
};


const editMode = ref(false);

onMounted(() => {
    if (route.name === 'admin.booking.edit') {
        editMode.value = true;
        getBooking();
    }

    flatpickr(".flatpickr", {
        enableTime: true,
        dateFormat: "Y-m-d h:i K",
        defaultHour: 10,
    });
});
</script>

<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">
                        <span v-if="editMode">Edit</span>
                        <span v-else>Create</span>
                        Booking
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <router-link to="/admin/dashboard">Home</router-link>
                        </li>
                        <li class="breadcrumb-item">
                            <router-link to="/admin/bookings">Bookings</router-link>
                        </li>
                        <li class="breadcrumb-item active">
                            <span v-if="editMode">Edit</span>
                            <span v-else>Create</span>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body ">
                            <Form @submit="handleSubmit" v-slot="{ errors }">
                                <ul class="nav nav-pills">
                                    <li class="nav-item"><a class="nav-link active show" href="#quick-view"
                                            data-toggle="tab">Quick View</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#edit" data-toggle="tab">Edit</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#guest-info" data-toggle="tab">Guest
                                            Info</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#services" data-toggle="tab">Services</a>
                                    </li>
                                    <li class="nav-item"><a class="nav-link" href="#lineitems" data-toggle="tab">Line
                                            Items</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#payments" data-toggle="tab">Payments</a>
                                    </li>
                                    <li class="nav-item"><a class="nav-link" href="#management"
                                            data-toggle="tab">Management</a></li>
                                </ul>
                                <hr />

                                <div class="tab-content">
                                    <!-- Tab Tab -->
                                    <div class="tab-pane active show" id="quick-view">
                                        <h3 class="text-center">Primary Details</h3>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>First Name: </label>
                                                    {{ form.firstname }}
                                                </div>
                                                <div class="form-group">
                                                    <label>Last Name: </label>
                                                    {{ form.lastname }}
                                                </div>
                                                <div class="form-group">
                                                    <label>Number of Adults: </label>
                                                    {{ form.adults }}
                                                </div>
                                                <div class="form-group">
                                                    <label>Number of Children: </label>
                                                    {{ form.children }}
                                                </div>
                                                <div class="form-group">
                                                    <label>Pets: </label>
                                                    {{ form.pets }}
                                                </div>
                                                <div class="form-group">
                                                    <label>Phone: </label>
                                                    {{ form.phone }}
                                                </div>
                                                <div class="form-group">
                                                    <label>Email: </label>
                                                    {{ form.email }}
                                                </div>
                                                <div class="form-group">
                                                    <label>City: </label>
                                                    {{ form.city }}
                                                </div>
                                                <div class="form-group">
                                                    <label>State / Country: </label>
                                                    {{ form.state }} / {{ form.country }}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Product: </label>
                                                    {{ form.product.name }}
                                                </div>
                                                <div class="form-group">
                                                    <label>Reservation ID#: </label>
                                                    {{ form.uniqid }}
                                                </div>
                                                <div class="form-group">
                                                    <label>Status: </label>
                                                    {{ form.status }}
                                                </div>
                                                <div class="form-group">
                                                    <label>Booked: </label>
                                                    {{ form.created_at }}
                                                </div>
                                                <div class="form-group">
                                                    <label>Date of Arrival: </label>
                                                    {{ form.date_start }}
                                                </div>
                                                <div class="form-group">
                                                    <label>Date of Departure: </label>
                                                    {{ form.date_end }}
                                                </div>
                                                <div class="form-group">
                                                    <label>Lodging Amount: </label>
                                                    ${{ form.lodging_amount }}

                                                    <span class="red" v-if="form.lodging_amount != form.lodging_amount">
                                                        NEW: ${{ form.lodging_amount }}
                                                    </span>
                                                </div>
                                                <div class="form-group">
                                                    <label>Sub Total: </label>
                                                    {{ form.sub_total_detail }}
                                                </div>
                                                <div class="form-group">
                                                    <label>Total: </label>
                                                    ${{ form.total_amount }}

                                                    <span class="red" v-if="form.total_amount != form.total_amount">
                                                        NEW: ${{ form.total_amount }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <h4 class="text-center">Services</h4>
                                                <table class="table table-hover">
                                                    <tbody>
                                                        <tr>
                                                            <th>Name</th>
                                                            <th>Date</th>
                                                            <th>Price</th>
                                                        </tr>
                                                        <tr v-if="form.services" v-for="service in form.services"
                                                            :key="service.id">
                                                            <td>{{ service.name }}</td>
                                                            <td>{{ service.date }}</td>
                                                            <td>${{ service.price }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="col-md-6">
                                                <h4 class="text-center">Line Items</h4>
                                                <table class="table table-hover">
                                                    <tbody>
                                                        <tr>
                                                            <th>Name</th>
                                                            <th>Price</th>
                                                        </tr>
                                                        <tr v-if="form.lineitems" v-for="lineitem in form.lineitems"
                                                            :key="lineitem.id">
                                                            <td>{{ lineitem.name }}</td>
                                                            <td>${{ lineitem.price }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>

                                        </div>
                                    </div>
                                    <!-- Tab Tab -->
                                    <div class="tab-pane" id="edit">
                                        <h3 class="text-center">Quick Edit</h3>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Date of Arrival: </label>
                                                    <input v-model="form.date_start" type="date" name="date_start"
                                                        placeholder="mm/dd/yyyy" class="form-control"
                                                        :class="{ 'is-invalid': errors.date_start }">
                                                    <span class="invalid-feedback">{{ errors.date_start }}</span>
                                                </div>
                                                <div class="form-group">
                                                    <label>Date of Departure: </label>
                                                    <input v-model="form.date_end" type="date" name="date_end"
                                                        placeholder="mm/dd/yyyy" class="form-control"
                                                        :class="{ 'is-invalid': errors.date_end }">
                                                    <span class="invalid-feedback">{{ errors.date_end }}</span>
                                                </div>
                                                <div class="form-group">
                                                    <label>Reservation Status: </label>
                                                    <select class="form-control" v-model="form.status">
                                                        <option disabled value="">Please select one</option>
                                                        <option value="new">New</option>
                                                        <option value="pending">Pending</option>
                                                        <option value="booked">Booked</option>
                                                        <option value="cancelled">Cancelled</option>
                                                    </select>
                                                    <span class="invalid-feedback">{{ errors.status }}</span>
                                                </div>



                                            </div>
                                            <div class="col-md-6">


                                                <div class="form-group">
                                                    <label>Lodging Amount: $</label>
                                                    <input v-model="form.lodging_amount" type="number" name="lodging_amount"
                                                        placeholder="000.00" class="form-control"
                                                        :class="{ 'is-invalid': errors.lodging_amount }">
                                                    <span class="invalid-feedback">{{ errors.lodging_amount }}</span>
                                                </div>
                                                <div class="form-group">
                                                    <label>Sub Total: </label>
                                                    <textarea rows="5" v-model="form.sub_total_detail" name="sub_total_detail"
                                                        id="sub_total_detail" placeholder="Sub total detail"
                                                        class="textarea form-control"
                                                        :class="{ 'is-invalid': errors.sub_total_detail }"></textarea>
                                                    <span class="invalid-feedback">{{ errors.sub_total_detail }}</span>
                                                </div>
                                                <div class="form-group">
                                                    <label>Total: $</label>
                                                    <input v-model="form.total_amount" type="number" name="total_amount"
                                                        placeholder="000.00" class="form-control"
                                                        :class="{ 'is-invalid': errors.total_amount }">
                                                    <span class="invalid-feedback">{{ errors.total_amount }}</span>
                                                </div>


                                            </div>
                                        </div>
                                    </div>
                                    <!-- Tab Pan-->
                                    <div class="tab-pane" id="guest-info">
                                        <h3 class="text-center">Address</h3>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>First Name: </label>
                                                    <input v-model="form.firstname" type="text" name="firstname"
                                                        placeholder="John" class="form-control"
                                                        :class="{ 'is-invalid': errors.firstname }">
                                                    <span class="invalid-feedback">{{ errors.firstname }}</span>
                                                </div>
                                                <div class="form-group">
                                                    <label>Last Name: </label>
                                                    <input v-model="form.lastname" type="text" name="lastname"
                                                        placeholder="Code" class="form-control"
                                                        :class="{ 'is-invalid': errors.lastname }">
                                                    <span class="invalid-feedback">{{ errors.lastname }}</span>
                                                </div>
                                                <div class="form-group">
                                                    <label>Adults: </label>
                                                    <input v-model="form.adults" type="number" name="adults" placeholder="#"
                                                        class="form-control" :class="{ 'is-invalid': errors.adults }">
                                                    <span class="invalid-feedback">{{ errors.adults }}</span>
                                                </div>
                                                <div class="form-group">
                                                    <label>Pets: </label>
                                                    <input v-model="form.pets" type="text" name="pets" placeholder="Cat"
                                                        class="form-control" :class="{ 'is-invalid': errors.pets }">
                                                    <span class="invalid-feedback">{{ errors.pets }}</span>
                                                </div>
                                                <div class="form-group">
                                                    <label>Phone: </label>
                                                    <input v-model="form.phone" type="text" name="phone"
                                                        placeholder="(###) ###-####  " class="form-control"
                                                        :class="{ 'is-invalid': errors.phone }">
                                                    <span class="invalid-feedback">{{ errors.phone }}</span>
                                                </div>
                                                <div class="form-group">
                                                    <label>Email: </label>
                                                    <input v-model="form.email" type="email" name="email"
                                                        placeholder="someone@gmail.com" class="form-control"
                                                        :class="{ 'is-invalid': errors.email }">
                                                    <span class="invalid-feedback">{{ errors.email }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Address Line 1: </label>
                                                    <input v-model="form.address_line_1" type="text" name="address_line_1"
                                                        placeholder="123 Street" class="form-control"
                                                        :class="{ 'is-invalid': errors.address_line_1 }">
                                                    <span class="invalid-feedback">{{ errors.address_line_1 }}</span>
                                                </div>
                                                <div class="form-group">
                                                    <label>Address: </label>
                                                    <input v-model="form.address_line_2" type="text" name="address_line_2"
                                                        placeholder="ABC Colony" class="form-control"
                                                        :class="{ 'is-invalid': errors.address_line_2 }">
                                                    <span class="invalid-feedback">{{ errors.address_line_2 }}</span>
                                                </div>
                                                <div class="form-group">
                                                    <label>City: </label>
                                                    <input v-model="form.city" type="text" name="city"
                                                        placeholder="New York" class="form-control"
                                                        :class="{ 'is-invalid': errors.city }">
                                                    <span class="invalid-feedback">{{ errors.city }}</span>
                                                </div>
                                                <div class="form-group">
                                                    <label>State: </label>
                                                    <input v-model="form.state" type="text" name="state"
                                                        placeholder="New York" class="form-control"
                                                        :class="{ 'is-invalid': errors.state }">
                                                    <span class="invalid-feedback">{{ errors.state }}</span>
                                                </div>
                                                <div class="form-group">
                                                    <label>Zip: </label>
                                                    <input v-model="form.zip" type="text" name="zip" placeholder="55555"
                                                        class="form-control" :class="{ 'is-invalid': errors.zip }">
                                                    <span class="invalid-feedback">{{ errors.zip }}</span>
                                                </div>
                                                <div class="form-group">
                                                    <label>Country: </label>
                                                    <input v-model="form.country" type="text" name="country"
                                                        placeholder="United States" class="form-control"
                                                        :class="{ 'is-invalid': errors.country }">
                                                    <span class="invalid-feedback">{{ errors.country }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Tab Pan-->
                                    <div class="tab-pane" id="services">
                                        <h3 class="text-center">Ordered Services</h3>
                                        <table class="table table-hover">
                                            <tbody>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Name</th>
                                                    <th>Date</th>
                                                    <th>Price</th>
                                                </tr>
                                                <tr v-if="form.services" v-for="service in form.services" :key="service.id">
                                                    <td>{{ service.id }}</td>
                                                    <td>{{ service.name }}</td>
                                                    <td>{{ service.date }}</td>
                                                    <td>${{ service.price }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- Tab Pan-->
                                    <div class="tab-pane" id="lineitems">
                                        <h3 class="text-center">Sold Line Items</h3>
                                        <table class="table table-hover">
                                            <tbody>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Name</th>
                                                    <th>Price</th>
                                                </tr>
                                                <tr v-for="lineitem in form.lineitems" :key="lineitem.id">
                                                    <td>{{ lineitem.id }}</td>
                                                    <td>{{ lineitem.name }}</td>
                                                    <td>${{ lineitem.price }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- Tab Pan-->
                                    <div class="tab-pane" id="payments">
                                        <h3 class="text-center">Payments</h3>
                                        <table class="table table-hover">
                                            <tbody>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Amount</th>
                                                    <th>Title</th>
                                                    <th>Status</th>
                                                    <th>Due On</th>
                                                    <th>Paid On</th>
                                                    <th>Method</th>
                                                    <th>Action</th>
                                                </tr>
                                                <tr v-if="form.payments" v-for="payment in form.payments" :key="payment.id">
                                                    <td>{{ payment.id }}</td>
                                                    <td>${{ payment.amount }}</td>
                                                    <td>{{ payment.title }}</td>
                                                    <td>{{ payment.status }}</td>
                                                    <td>{{ payment.date_due }}</td>
                                                    <td>{{ payment.date_paid }}</td>
                                                    <td>{{ payment.method }}</td>
                                                    <td>
                                                        <a href="#" @click="editPaymentModal(payment)"><i
                                                                class="fa fa-edit "></i> Edit </a>
                                                        |
                                                        <a href="#" @click="deletePayment(payment.id)"><i
                                                                class="fa fa-trash red"></i></a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <div class="container text-center">
                                            <button @click="addPaymentModal" type="button" class="btn btn-sm btn-info">
                                                <i class="fa fa-plus"></i> Add Payment
                                            </button>
                                        </div>
                                    </div>
                                    <!-- Tab Pan-->
                                    <div class="tab-pane" id="management">
                                        <h3 class="text-center">Management</h3>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Housekeeper: </label>
                                                    <select class="form-control" v-model="form.created_by">
                                                        <option disabled value="">Please select one</option>
                                                    </select>
                                                    <span class="invalid-feedback">{{ errors.xxx }}</span>
                                                </div>
                                                <div class="form-group">
                                                    <label>Created By: </label>
                                                    <select class="form-control" v-model="form.created_by">
                                                        <option disabled value="">Please select one</option>
                                                        <option value="admin">Admin</option>
                                                        <option value="agent">Agent</option>
                                                        <option value="guest">Guest</option>
                                                    </select>
                                                    <span class="invalid-feedback">{{ errors.created_by }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Notes: </label>
                                                <textarea v-model="form.notes" name="notes" id="notes"
                                                    placeholder="Confidential notes for admin..."
                                                    class="textarea form-control"
                                                    :class="{ 'is-invalid': errors.notes }"></textarea>
                                                <span class="invalid-feedback">{{ errors.notes }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.tab-pane -->
                                </div>

                                <button type="submit" class="btn btn-primary">Submit</button>
                            </Form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    
</template>

<style scoped>
.checkbox-item {
    float: left;
}

.checkbox-label {
    font-weight: normal !important;
}

.checkbox-label input[type="checkbox"] {
    margin-right: 8px;
}
</style>