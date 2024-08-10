<template>
    <AdminLayout>
        <v-col cols="12">
            <v-card elevation="0">
                <v-card-title class="text-center" style="background-color:lightblue; color:black;">
                    Student Payments
                    <v-spacer></v-spacer>
                </v-card-title>
                <br>
                <v-card-text>
                    <v-btn color="primary" @click="importPayments" class="mr-4" label elevation="5" style="text-transform: capitalize;">
                        <v-icon left>mdi-upload</v-icon> Import
                    </v-btn>
                    <v-btn @click="printPayments" class="mr-4" label elevation="5" color="red" style="text-transform: capitalize;">
                        <v-icon left>mdi-printer</v-icon> Print
                    </v-btn>
                    <v-btn color="green" @click="exportPayments" class="mr-4" label elevation="5" style="text-transform: capitalize;">
                        <v-icon left>mdi-download</v-icon> Export
                    </v-btn>
                    <v-btn color="blue" @click="dialog.add = true" label elevation="5" style="text-transform: capitalize;">
                        <v-icon left>mdi-plus</v-icon> Add Payment
                    </v-btn>
                </v-card-text>
                <br>
                <v-text-field
                    v-model="search"
                    label="Search"
                    class="mb-2"
                    append-icon="mdi-magnify"
                    single-line
                    hide-details
                    variant="underlined"
                ></v-text-field>
                <br>
                <!-- Data Table Container with Scroll -->
                <v-data-table
                    :headers="headers"
                    :items="paginatedPayments"
                    :search="search"
                    item-key="id"
                    class="elevation-1"
                >
                <template v-slot:item.actions="{ item }">
                    <v-chip icon color="white" @click="openEditDialog(item)" elevation="0">
                      <v-icon color="green">mdi-pencil</v-icon>
                    </v-chip>
                    <v-chip icon color="white" @click="openViewDialog(item)" elevation="0">
                      <v-icon color="primary">mdi-eye</v-icon>
                    </v-chip>
                    <v-chip icon color="white" @click="openDeleteDialog(item)">
                      <v-icon color="red">mdi-delete</v-icon>
                    </v-chip>
                </template>
                </v-data-table>
            </v-card>
            <!-- Add Payment Dialog -->
            <v-dialog v-model="dialog.add" max-width="600px">
                <v-card>
                    <v-toolbar color="white">
                        <v-card-title style="font-size:25px;font-weight: 900;">Add Payment</v-card-title>
                        <v-spacer></v-spacer>
                        <v-btn @click="dialog.add = false" elevation="0" color="black" style="text-transform: capitalize;" icon><v-icon>mdi-close</v-icon></v-btn>
                    </v-toolbar>
                    <v-card-text>
                        <v-select
                            v-model="newPayment.student_id"
                            :items="students"
                            item-value="id"
                            item-title="name"
                            label="Select Student"
                            dense
                            variant="underlined"
                            prepend-inner-icon="mdi-account-group"
                        ></v-select>
                        <v-select
                            v-model="newPayment.payment_mode_id"
                            :items="paymentModes"
                            item-value="id"
                            item-title="mode_name"
                            label="Payment Mode"
                            dense
                            variant="underlined"
                            prepend-inner-icon="mdi-cash"
                        ></v-select>
                        <v-text-field
                            v-model="newPayment.payment_amount"
                            label="Amount"
                            dense
                            variant="underlined"
                            prepend-inner-icon="mdi-cash"
                        ></v-text-field>
                        <v-text-field
                            v-model="newPayment.payment_date"
                            label="Date"
                            dense
                            variant="underlined"
                            prepend-inner-icon="mdi-calendar"
                        ></v-text-field>
                        <v-text-field
                            v-model="newPayment.reference_number"
                            label="Reference Number"
                            dense
                            variant="underlined"
                            prepend-inner-icon="mdi-tag"
                        ></v-text-field>
                        <v-btn
                            @click="addPayment"
                            elevation="3"
                            class="mr-4"
                            color="green"
                            style="text-transform: capitalize;"
                        >
                            Add
                        </v-btn>
                    </v-card-text>
                </v-card>
            </v-dialog>
            <!-- Edit Dialog -->
            <v-dialog v-model="dialog.edit" max-width="600px">
                <v-card>
                    <v-card-title>Edit Payment</v-card-title>
                    <v-card-text>
                        <v-text-field v-model="selectedPayment.student_id" label="Student ID" dense></v-text-field>
                        <v-select
                            v-model="selectedPayment.payment_mode_id"
                            :items="paymentModes"
                            item-value="id"
                            item-title="mode_name"
                            label="Payment Mode"
                            dense
                        ></v-select>
                        <v-text-field v-model="selectedPayment.payment_amount" label="Amount" dense></v-text-field>
                        <v-text-field v-model="selectedPayment.payment_date" label="Date" dense></v-text-field>
                        <v-text-field v-model="selectedPayment.reference_number" label="Reference Number" dense></v-text-field>
                        <v-btn @click="saveEdit" elevation="3" class="mr-4" color="green" style="text-transform: capitalize;">Save</v-btn>
                        <v-btn @click="dialog.edit = false" elevation="3" color="red" style="text-transform: capitalize;">Cancel</v-btn>
                    </v-card-text>
                </v-card>
            </v-dialog>
            <!-- View Dialog -->
            <v-dialog v-model="dialog.view" max-width="500px">
                <v-card>
                    <v-toolbar color="white">
                    <v-card-title style="font-weight: 900;">View Payment</v-card-title>
                    </v-toolbar>
                    <v-card-text>
                        <p>Student Name: {{ selectedPayment.student.name }}</p>
                        <p>Payment Mode: {{ selectedPayment.payment_mode.mode_name }}</p>
                        <p>Amount: {{ selectedPayment.payment_amount }}</p>
                        <p>Date: {{ selectedPayment.payment_date }}</p>
                        <p>Reference Number: {{ selectedPayment.reference_number }}</p>
                        <br>
                        <em><p>Processed by: {{ selectedPayment.user.full_name }}</p></em>
                    </v-card-text>
                </v-card>
            </v-dialog>
            <!-- Delete Dialog -->
            <v-dialog v-model="dialog.delete" max-width="500px">
                <v-card>
                    <v-card-title style="background-color: green; color: white; text-transform: uppercase;">Delete Payment</v-card-title>
                    <v-card-text>
                        <div>Are you sure you want to delete this payment?</div>
                        <br>
                        <v-btn @click="confirmDelete" elevation="3" class="mr-4" color="green" style="text-transform: capitalize;">Confirm</v-btn>
                        <v-btn @click="dialog.delete = false" elevation="3" color="red" style="text-transform: capitalize;">Cancel</v-btn>
                    </v-card-text>
                </v-card>
            </v-dialog>
        </v-col>
    </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Inertia } from '@inertiajs/inertia';

const { props } = usePage();
const payments = ref(props.payments || []);
const paymentModes = ref(props.paymentModes || []);
const students = ref(props.students || []);
console.log('Students data:', students.value);

// Initialize dialog states
const dialog = ref({
    add: false,
    edit: false,
    view: false,
    delete: false,
});

const selectedPayment = ref(null);
const newPayment = ref({
    student_id: '',
    payment_mode_id: '',
    payment_amount: '',
    payment_date: '',
    reference_number: ''
});

const headers = [
    { title: 'Student Name', value: 'student.name' },
    { title: 'Course Title', value: 'student.course.course_name' },
    { title: 'Payment Mode', value: 'payment_mode.mode_name' },
    { title: 'Fee Paid', value: 'payment_amount' },
    { title: 'Date', value: 'payment_date' },
    { title: 'Reference Number', value: 'reference_number' },
    { title: 'Fee Balance', value: 'balance' },
    { title: 'Processed by', value: 'user.full_name' },
    { title: 'Actions', value: 'actions', sortable: false },
];

const page = ref(1);
const itemsPerPage = ref(7);
const search = ref('');

// Utility function to get student balance
const getStudentBalance = (studentId) => {
    const student = students.value.find(s => s.id === studentId);
    return student ? student.balance : 0;
};

const filteredPayments = computed(() => {
    const lowerSearch = search.value.toLowerCase();
    return payments.value.map(payment => ({
        ...payment,
        balance: getStudentBalance(payment.student_id)
    })).filter(payment =>
        Object.values(payment).some(value =>
            typeof value === 'string' && value.toLowerCase().includes(lowerSearch)
        )
    );
});

const paginatedPayments = computed(() => {
    const start = (page.value - 1) * itemsPerPage.value;
    const end = start + itemsPerPage.value;
    return filteredPayments.value.slice(start, end);
});

const importPayments = () => {
    // Logic to import payments
};

const printPayments = () => {
    // Logic to print payments
};

const exportPayments = () => {
    // Logic to export payments
};

const addPayment = () => {
    Inertia.post('/payments', newPayment.value)
        .then(() => {
            dialog.value.add = false;
            // Clear newPayment fields
            newPayment.value = {
                student_id: '',
                payment_mode_id: '',
                payment_amount: '',
                payment_date: '',
                reference_number: ''
            };
        });
};

const openEditDialog = (payment) => {
    selectedPayment.value = { ...payment };
    dialog.value.edit = true;
};

const saveEdit = () => {
    Inertia.put(`/payments/${selectedPayment.value.id}`, selectedPayment.value)
        .then(() => {
            dialog.value.edit = false;
        });
};

const openViewDialog = (payment) => {
    selectedPayment.value = { ...payment };
    dialog.value.view = true;
};

const openDeleteDialog = (payment) => {
    selectedPayment.value = { ...payment };
    dialog.value.delete = true;
};

const confirmDelete = () => {
    Inertia.delete(`/payments/${selectedPayment.value.id}`)
        .then(() => {
            dialog.value.delete = false;
        });
};
</script>

<style scoped>
/* Add any styles you need */
</style>
