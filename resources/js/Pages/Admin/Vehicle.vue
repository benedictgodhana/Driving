<template>
    <AdminLayout>
        <v-col cols="12">
            <v-card elevation="0">
                <v-card-title class="text-center" style="background-color:lightblue; color:black;">
                    Vehicle List
                    <v-spacer></v-spacer>
                </v-card-title>
                <br>
                <v-card-text>
                    <v-btn color="primary" @click="importVehicles" class="mr-4" label elevation="5" style="text-transform: capitalize;">
                        <v-icon left>mdi-upload</v-icon> Import
                    </v-btn>
                    <v-btn @click="printVehicles" class="mr-4" label elevation="5" color="red" style="text-transform: capitalize;">
                        <v-icon left>mdi-printer</v-icon> Print
                    </v-btn>
                    <v-btn color="green" @click="exportVehicles" class="mr-4" label elevation="5" style="text-transform: capitalize;">
                        <v-icon left>mdi-download</v-icon> Export
                    </v-btn>
                    <v-btn color="blue" @click="dialog.add = true" label elevation="5" style="text-transform: capitalize;">
                        <v-icon left>mdi-plus</v-icon> Add Vehicle
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
                    :items="paginatedVehicles"
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
            <!-- Add Vehicle Dialog -->
            <v-dialog v-model="dialog.add" max-width="600px">
                <v-card>
                    <v-toolbar color="white">
                        <v-card-title style="font-size:25px;font-weight: 900;">Add Vehicle</v-card-title>
                        <v-spacer></v-spacer>
                        <v-btn @click="dialog.add = false" elevation="0" color="black" style="text-transform: capitalize;" icon><v-icon>mdi-close</v-icon></v-btn>
                    </v-toolbar>
                    <v-card-text>
                        <v-text-field
                            v-model="newVehicle.reg_no"
                            label="Vehicle Plate Number"
                            dense
                            variant="underlined"
                            prepend-inner-icon="mdi-car"
                        ></v-text-field>

                        <v-btn
                            @click="addVehicle"
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
            <!-- Edit Vehicle Dialog -->
            <v-dialog v-model="dialog.edit" max-width="600px">
                <v-card>
                    <v-card-title>Edit Vehicle</v-card-title>
                    <v-card-text>
                        <v-text-field v-model="selectedVehicle.reg_no" label="Vehicle Plate Number" dense variant="underlined"></v-text-field>
                        <v-btn @click="saveEdit" elevation="3" class="mr-4" color="green" style="text-transform: capitalize;">Save</v-btn>
                        <v-btn @click="dialog.edit = false" elevation="3" color="red" style="text-transform: capitalize;">Cancel</v-btn>
                    </v-card-text>
                </v-card>
            </v-dialog>
            <!-- View Vehicle Dialog -->
            <v-dialog v-model="dialog.view" max-width="500px">
                <v-card>
                    <v-card-title>View Vehicle</v-card-title>
                    <v-card-text>
                        <p>Plate Number: {{ selectedVehicle.reg_no }}</p>
                        <p>Added by: {{ selectedVehicle.user.full_name }}</p>
                    </v-card-text>
                </v-card>
            </v-dialog>
            <!-- Delete Vehicle Dialog -->
            <v-dialog v-model="dialog.delete" max-width="500px">
                <v-card>
                    <v-card-title style="background-color: green; color: white; text-transform: uppercase;">Delete Vehicle</v-card-title>
                    <v-card-text>
                        <div>Are you sure you want to delete this vehicle?</div>
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
const vehicles = ref(props.vehicles || []); // Ensure it's an array, defaulting to empty array if undefined

const dialog = ref({
    add: false,
    edit: false,
    view: false,
    delete: false,
});

const selectedVehicle = ref(null);
const newVehicle = ref({
    vehicle_code: '',
    vehicle_name: '',
    vehicle_plate_number: '',
    vehicle_address: ''
});

const headers = [
    { title: 'Vehicle Plate Number', value: 'reg_no' },
    { title: 'Vehicle Name', value: 'user.full_name' },
    { title: 'Actions', value: 'actions', sortable: false },
];

const page = ref(1);
const itemsPerPage = ref(7);
const search = ref('');

const filteredVehicles = computed(() => {
    const lowerSearch = search.value.toLowerCase();
    return vehicles.value.filter(vehicle =>
        Object.values(vehicle).some(value =>
            typeof value === 'string' && value.toLowerCase().includes(lowerSearch)
        )
    );
});

const paginatedVehicles = computed(() => {
    const start = (page.value - 1) * itemsPerPage.value;
    const end = start + itemsPerPage.value;
    return filteredVehicles.value.slice(start, end);
});

const pageCount = computed(() => {
    return Math.ceil(filteredVehicles.value.length / itemsPerPage.value);
});

const saveEdit = () => {
    console.log('Saving edit', selectedVehicle.value);
    Inertia.post('/vehicles/update', {
        id: selectedVehicle.value.id,
        vehicle_code: selectedVehicle.value.vehicle_code,
        vehicle_name: selectedVehicle.value.vehicle_name,
        vehicle_plate_number: selectedVehicle.value.vehicle_plate_number,
        vehicle_address: selectedVehicle.value.vehicle_address
    })
        .then(() => {
            console.log('Vehicle details updated');
        })
        .catch(error => {
            console.error('Error updating vehicle details:', error);
        });
    dialog.value.edit = false;
};

const confirmDelete = () => {
    console.log('Deleting vehicle');
    Inertia.post('/vehicles/delete', { id: selectedVehicle.value.id })
        .then(() => {
            console.log('Vehicle deleted');
            vehicles.value = vehicles.value.filter(vehicle => vehicle.id !== selectedVehicle.value.id);
        })
        .catch(error => {
            console.error('Error deleting vehicle:', error);
        });
    dialog.value.delete = false;
};

const addVehicle = () => {
    console.log('Adding vehicle', newVehicle.value);
    Inertia.post('/vehicles/create', newVehicle.value)
        .then(() => {
            console.log('Vehicle added');
        })
        .catch(error => {
            console.error('Error adding vehicle:', error);
        });
    dialog.value.add = false;
};

const openEditDialog = (vehicle) => {
    selectedVehicle.value = { ...vehicle };
    dialog.value.edit = true;
};

const openViewDialog = (vehicle) => {
    selectedVehicle.value = { ...vehicle };
    dialog.value.view = true;
};

const openDeleteDialog = (vehicle) => {
    selectedVehicle.value = { ...vehicle };
    dialog.value.delete = true;
};

const importVehicles = () => {
    console.log('Importing vehicles...');
    // Add logic for importing vehicles
};

const printVehicles = () => {
    console.log('Printing vehicles...');
    // Add logic for printing vehicles
};

const exportVehicles = () => {
    console.log('Exporting vehicles...');
    // Add logic for exporting vehicles
};
</script>
