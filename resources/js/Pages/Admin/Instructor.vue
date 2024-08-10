<template>
    <AdminLayout>
        <v-col cols="12">
            <v-card elevation="0">
                <v-card-title class="text-center" style="background-color:lightblue; color:black;">
                    Instructor List
                    <v-spacer></v-spacer>
                </v-card-title>
                <br>
                <v-card-text>
                    <v-btn color="primary" @click="importInstructors" class="mr-4" label elevation="5" style="text-transform: capitalize;">
                        <v-icon left>mdi-upload</v-icon> Import
                    </v-btn>
                    <v-btn @click="printInstructors" class="mr-4" label elevation="5" color="red" style="text-transform: capitalize;">
                        <v-icon left>mdi-printer</v-icon> Print
                    </v-btn>
                    <v-btn color="green" @click="exportInstructors" class="mr-4" label elevation="5" style="text-transform: capitalize;">
                        <v-icon left>mdi-download</v-icon> Export
                    </v-btn>
                    <v-btn color="blue" @click="dialog.add = true" label elevation="5" style="text-transform: capitalize;">
                        <v-icon left>mdi-plus</v-icon> Add Instructor
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
                <v-progress-linear
                    v-if="loading"
                    indeterminate
                    color="primary"
                    height="4"
                ></v-progress-linear>
                <v-data-table
                    :headers="headers"
                    :items="filteredInstructors"
                    :search="search"
                    items-per-page="7"
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
            <!-- Add Instructor Dialog -->
            <v-dialog v-model="dialog.add" max-width="600px">
                <v-card>
                    <v-toolbar color="white">
                        <v-card-title style="font-size:25px;font-weight: 900;">Add Instructor</v-card-title>
                        <v-spacer></v-spacer>
                        <v-btn @click="dialog.add = false" elevation="0" color="black" style="text-transform: capitalize;" icon><v-icon>mdi-close</v-icon></v-btn>
                    </v-toolbar>
                    <v-card-text>
                        <v-form @submit.prevent="submitForm">
                            <v-text-field
                                v-model="form.name"
                                label="Name"
                                dense
                                variant="underlined"
                                prepend-inner-icon="mdi-account"
                                :rules="[v => !!v || 'Name is required']"
                            ></v-text-field>
                            <v-text-field
                                v-model="form.email"
                                label="Email"
                                dense
                                variant="underlined"
                                prepend-inner-icon="mdi-email"
                                :rules="[v => /.+@.+/.test(v) || 'Email must be valid']"
                            ></v-text-field>
                            <v-text-field
                                v-model="form.tel"
                                label="Phone Number"
                                dense
                                variant="underlined"
                                prepend-inner-icon="mdi-phone"
                                :rules="[v => !!v || 'Phone number is required']"
                            ></v-text-field>
                            <v-text-field
                                v-model="form.id_number"
                                label="ID Number"
                                dense
                                variant="underlined"
                                prepend-inner-icon="mdi-card"
                                :rules="[v => !!v || 'ID Number is required']"
                            ></v-text-field>
                            <v-btn
                                type="submit"
                                :loading="loading"
                                elevation="3"
                                class="mr-4"
                                color="green"
                                style="text-transform: capitalize;"
                            >
                                Add
                            </v-btn>
                        </v-form>
                    </v-card-text>
                </v-card>
            </v-dialog>
            <!-- Edit Dialog -->
            <v-dialog v-model="dialog.edit" max-width="600px">
                <v-card>
                    <v-card-title>Edit Instructor</v-card-title>
                    <v-card-text>
                        <v-text-field v-model="selectedInstructor.name" label="Name" dense></v-text-field>
                        <v-text-field v-model="selectedInstructor.email" label="Email" dense></v-text-field>
                        <v-text-field v-model="selectedInstructor.tel" label="Phone Number" dense></v-text-field>
                        <v-text-field v-model="selectedInstructor.id_number" label="ID Number" dense></v-text-field>
                        <v-btn @click="saveEdit" elevation="3" class="mr-4" color="green" style="text-transform: capitalize;">Save</v-btn>
                        <v-btn @click="dialog.edit = false" elevation="3" color="red" style="text-transform: capitalize;">Cancel</v-btn>
                    </v-card-text>
                </v-card>
            </v-dialog>
            <v-dialog v-model="dialog.view" max-width="500px">
                <v-card>
                    <v-card-title>View Instructor</v-card-title>
                    <v-card-text>
                        <p>Name: {{ selectedInstructor.name }}</p>
                        <p>Email: {{ selectedInstructor.email }}</p>
                        <p>Phone Number: {{ selectedInstructor.tel }}</p>
                        <p>ID Number: {{ selectedInstructor.id_number }}</p>
                        <br>
                        <em><p>Added by: {{ selectedInstructor.user.full_name }}</p></em>
                    </v-card-text>
                </v-card>
            </v-dialog>
            <v-dialog v-model="dialog.delete" max-width="500px">
                <v-card>
                    <v-card-title style="background-color: green; color: white; text-transform: uppercase;">Delete Instructor</v-card-title>
                    <v-card-text>
                        <div>Are you sure you want to delete this instructor?</div>
                        <br>
                        <v-btn @click="confirmDelete" elevation="3" class="mr-4" color="green" style="text-transform: capitalize;">Confirm</v-btn>
                        <v-btn @click="dialog.delete = false" elevation="3" color="red" style="text-transform: capitalize;">Cancel</v-btn>
                    </v-card-text>
                </v-card>
            </v-dialog>

            <v-snackbar v-model="snackbar.show" :color="snackbar.color" timeout="3000">
                {{ snackbar.message }}
                <template v-slot:action="{ attrs }">
                    <v-btn color="white" text v-bind="attrs" @click="snackbar.show = false">Close</v-btn>
                </template>
            </v-snackbar>
        </v-col>
    </AdminLayout>
</template>
<script setup>
import { ref, computed } from 'vue';
import { usePage, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Inertia } from '@inertiajs/inertia';

const { props } = usePage();
const instructors = ref(props.instructors || []);

const dialog = ref({
    add: false,
    edit: false,
    view: false,
    delete: false,
});

const selectedInstructor = ref(null);

const form = useForm({
    name: '',
    email: '',
    tel: '',
    id_number: ''
});

const headers = [
    { title: 'Name', value: 'name' },
    { title: 'Email', value: 'email' },
    { title: 'Phone Number', value: 'tel' },
    { title: 'ID Number', value: 'id_number' },
    { title: 'Created by', value: 'user.full_name' },
    { title: 'Actions', value: 'actions', sortable: false },
];



const page = ref(1);
const itemsPerPage = ref(7);
const search = ref('');

const filteredInstructors = computed(() => {
    const lowerSearch = search.value.toLowerCase();
    return instructors.value.filter(instructor =>
        Object.values(instructor).some(value =>
            typeof value === 'string' && value.toLowerCase().includes(lowerSearch)
        )
    );
});

const paginatedInstructors = computed(() => {
    const start = (page.value - 1) * itemsPerPage.value;
    const end = start + itemsPerPage.value;
    return filteredInstructors.value.slice(start, end);
});

const loading = ref(false);
const snackbar = ref({
    show: false,
    message: '',
    color: 'success',
});

function importInstructors() {
    // Your logic for importing instructors
}

function printInstructors() {
    // Your logic for printing instructors
}

function exportInstructors() {
    // Your logic for exporting instructors
}

function submitForm() {
    form.post('/instructors', {
        onStart: () => loading.value = true,
        onFinish: () => loading.value = false,
        onSuccess: () => {
            dialog.value.add = false;
            snackbar.value = {
                show: true,
                message: 'Instructor added successfully!',
                color: 'success',
            };
            form.reset();
        },
        onError: () => {
            snackbar.value = {
                show: true,
                message: 'Failed to add instructor.',
                color: 'error',
            };
        }
    });
}

function openEditDialog(item) {
    selectedInstructor.value = { ...item };
    dialog.value.edit = true;
}

function saveEdit() {
    Inertia.put(`/instructors/${selectedInstructor.value.id}`, selectedInstructor.value, {
        onStart: () => loading.value = true,
        onFinish: () => loading.value = false,
        onSuccess: () => {
            dialog.value.edit = false;
            snackbar.value = {
                show: true,
                message: 'Instructor updated successfully!',
                color: 'success',
            };
        },
        onError: () => {
            snackbar.value = {
                show: true,
                message: 'Failed to update instructor.',
                color: 'error',
            };
        }
    });
}

function openViewDialog(item) {
    selectedInstructor.value = item;
    dialog.value.view = true;
}

function openDeleteDialog(item) {
    selectedInstructor.value = item;
    dialog.value.delete = true;
}
function confirmDelete() {
    // Set the ID in the form
    form.id = selectedInstructor.value.id;

    form.delete(`/instructors/${form.id}`, {
        onStart: () => {
            loading.value = true;
        },
        onFinish: () => {
            loading.value = false;
        },
        onSuccess: () => {
            dialog.value.delete = false;
            snackbar.value = {
                show: true,
                message: 'Instructor deleted successfully!',
                color: 'success',
            };
        },
        onError: () => {
            snackbar.value = {
                show: true,
                message: 'Failed to delete instructor.',
                color: 'error',
            };
        }
    });
}
</script>
