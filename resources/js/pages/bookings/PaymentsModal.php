

const editPaymentModal = (payment) => {
   this.editmode = true;
   this.form_payment.reset();
   $('#addEditPayment').modal('show');
   this.form_payment.fill(payment);
}
const addPaymentModal = () => {
   this.editmode = false;
   this.form_payment.reset();
   $('#addEditPayment').modal('show');
}
const deletePayment = (id) => {
   swal({
       title: 'Are you sure?',
       text: "You won't be able to revert this!",
       type: 'warning',
       showCancelButton: true,
       confirmButtonColor: '#3085d6',
       cancelButtonColor: '#d33',
       confirmButtonText: 'Yes, delete it!'
   }).then((result) => {
       // Send request to the server
       if (result.value) {
           this.form.delete('/api/reservationpayment/' + id).then(() => {
               swal(
                   'Deleted!',
                   'Your file has been deleted.',
                   'success'
               )
               Fire.$emit('AfterCreatePayment');
           }).catch(() => {
               swal("Failed!", "There was something wronge.", "warning");
           });
       }
   })
}
const createPayment = () => {
   this.$Progress.start();
   this.form_payment.post('/api/reservationpayment')
       .then(() => {
           Fire.$emit('AfterCreatePayment');
           $('#addEditPayment').modal('hide')

           toast({
               type: 'success',
               title: 'Payment record added successfully.'
           })
           this.$Progress.finish();

       })
       .catch(() => {
       })
}
const updatePayment = () => {
   this.$Progress.start();
   // console.log('Editing data');
   this.form_payment.put('/api/reservationpayment/' + this.form_payment.id)
       .then(() => {
           // success
           $('#addEditPayment').modal('hide');
           swal(
               'Updated!',
               'Payment record updated successfully.',
               'success'
           )
           this.$Progress.finish();
           Fire.$emit('AfterCreatePayment');
       })
       .catch(() => {
           this.$Progress.fail();
       });
}




<div class="modal fade" id="addEditPayment" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
         <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Add New Payment</h5>
                  <h5 class="modal-title" v-show="editmode" id="addNewLabel">Update Payment</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <Form @submit.prevent="editmode ? updatePayment() : createPayment()">
                  <div class="modal-body">
                     <div class="form-group">
                        <input v-model="form_payment.title" type="text" placeholder="Title" class="form-control" :class="{ 'is-invalid': errors.title }">
                     </div>
                     <div class="form-group">
                        <input v-model="form_payment.amount" type="text" placeholder="000.00" class="form-control" :class="{ 'is-invalid': errors.amount }">
                     </div>
                     <div class="form-group">
                        <select class="form-control" v-model="form_payment.status">
                           <option disabled value="">Please select one</option>
                           <option value="pending">Pending</option>
                           <option value="paid">Paid</option>
                           <option value="cancelled">Cancelled</option>
                        </select>
                     </div>
                     <div class="form-group">
                        <input v-model="form_payment.date_due" type="date" placeholder="date_due" class="form-control" :class="{ 'is-invalid': errors.date_due }">
                     </div>
                     <div class="form-group">
                        <input v-model="form_payment.date_paid" type="date" placeholder="date_paid" class="form-control" :class="{ 'is-invalid': errors.date_paid }">
                     </div>
                     <div class="form-group">
                        <select class="form-control" v-model="form_payment.method">
                           <option disabled value="">Please select one</option>
                           <option value="paypal">PayPal</option>
                           <option value="stripe">Stripe</option>
                           <option value="cheque">Cheque</option>
                           <option value="cash">Cash</option>
                        </select>
                     </div>
                  </div>
                  <div class="modal-footer">
                     <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                     <button v-show="editmode" type="submit" class="btn btn-success">Update</button>
                     <button v-show="!editmode" type="submit" class="btn btn-primary">Create</button>
                  </div>
               </Form>
            </div>
         </div>
      </div>