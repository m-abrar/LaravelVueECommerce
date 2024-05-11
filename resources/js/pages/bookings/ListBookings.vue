<script setup>
import { onMounted, ref, computed } from 'vue';
import Swal from 'sweetalert2';
import axios from 'axios';

const selectedType = ref();
const bookingTypes = ref([]);
const bookingsList = ref([]);


const getBookings = (type) => {
    selectedType.value = type;
    const params = {};
    if (type) {
        params.type = type;
    }
    axios.get('/api/bookings', {
        params: params,
    })
        .then((response) => {
            bookingsList.value = response.data;
        })
};


const deleteBooking = (id) => {
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
            axios.delete(`/api/bookings/${id}`)
                .then((response) => {
                    updateBookingTypesCount(id);
                    bookingsList.value.data = bookingsList.value.data.filter(bookingsList => bookingsList.id !== id);
                    Swal.fire(
                        'Deleted!',
                        'Your file has been deleted.',
                        'success'
                    )
                });
        }
    })
};

onMounted(() => {
    getBookings();
});
</script>
<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Orders</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Orders</li>
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
                            <router-link to="/admin/booking/create">
                                <button class="btn btn-primary"><i class="fa fa-plus-circle mr-1"></i> Add New
                                    Booking</button>
                            </router-link>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">First Name</th>
                                        <th scope="col">Last Name</th>
                                        <th scope="col">Total Amount</th>
                                        <th scope="col">From Date</th>
                                        <th scope="col">To Date</th>
                                        <th scope="col">Options</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(booking, index) in bookingsList.data" :key="booking.id">
                                        <td>{{ index + 1 }}</td>
                                        <td>{{ booking.firstname }}</td>
                                        <td>{{ booking.lastname }}</td>
                                        <td>{{ booking.total_amount }}</td>
                                        <td>{{ booking.date_start }}</td>
                                        <td>{{ booking.date_end }}</td>
                                        <!-- <td>
                                            <span class="badge" :class="`badge-${bookings.status.color}`">{{
                                                bookings.status.name }}</span>
                                        </td> -->
                                        <td>
                                            <router-link :to="`/admin/booking/${booking.id}/edit`">
                                                <i class="fa fa-edit mr-2"></i>
                                            </router-link>

                                            <a href="#" @click.prevent="deleteBookings(booking.id)">
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