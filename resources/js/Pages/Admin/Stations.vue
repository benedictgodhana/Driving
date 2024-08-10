<template>
    <AdminLayout>
        <v-col cols="12">
            <v-card elevation="0">
                <v-card-title class="text-center" style="background-color:lightblue; color:black;">
                    Station List
                    <v-spacer></v-spacer>
                </v-card-title>
                <br>
                <v-card-text>
                    <v-btn color="primary" @click="importStations" class="mr-4" label elevation="5" style="text-transform: capitalize;">
                        <v-icon left>mdi-upload</v-icon> Import
                    </v-btn>
                    <v-btn @click="printStations" class="mr-4" label elevation="5" color="red" style="text-transform: capitalize;">
                        <v-icon left>mdi-printer</v-icon> Print
                    </v-btn>
                    <v-btn color="green" @click="exportStations" class="mr-4" label elevation="5" style="text-transform: capitalize;">
                        <v-icon left>mdi-download</v-icon> Export
                    </v-btn>
                    <v-btn color="blue" @click="dialog.add = true" label elevation="5" style="text-transform: capitalize;">
                        <v-icon left>mdi-plus</v-icon> Add Station
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
                    :items="paginatedStations"
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
            <!-- Add Station Dialog -->
            <v-dialog v-model="dialog.add" max-width="600px">
                <v-card>
                    <v-toolbar color="white">
                        <v-card-title style="font-size:25px;font-weight: 900;">Add Station</v-card-title>
                        <v-spacer></v-spacer>
                        <v-btn @click="dialog.add = false" elevation="0" color="black" style="text-transform: capitalize;" icon><v-icon>mdi-close</v-icon></v-btn>


                    </v-toolbar>
                    <v-card-text>
    <v-text-field
        v-model="newStation.station_code"
        label="Station Code"
        dense
        variant="underlined"
        prepend-inner-icon="mdi-tag"
    ></v-text-field>
    <v-text-field
        v-model="newStation.station_name"
        label="Station Name"
        dense
        variant="underlined"
        prepend-inner-icon="mdi-city"
    ></v-text-field>
    <v-text-field
        v-model="newStation.station_phone_number"
        label="Phone Number"
        dense
        variant="underlined"
        prepend-inner-icon="mdi-phone"
    ></v-text-field>
    <v-text-field
        v-model="newStation.address"
        label="Address"
        dense
        variant="underlined"
        prepend-inner-icon="mdi-map-marker"
    ></v-text-field>
    <v-btn
        @click="addStation"
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
                    <v-card-title>Edit Station</v-card-title>
                    <v-card-text>
                        <v-text-field v-model="selectedStation.station_code" label="Station Code" dense></v-text-field>
                        <v-text-field v-model="selectedStation.station_name" label="Station Name" dense></v-text-field>
                        <v-btn @click="saveEdit" elevation="3" class="mr-4" color="green" style="text-transform: capitalize;">Save</v-btn>
                        <v-btn @click="dialog.edit = false" elevation="3" color="red" style="text-transform: capitalize;">Cancel</v-btn>
                    </v-card-text>
                </v-card>
            </v-dialog>
            <v-dialog v-model="dialog.view" max-width="500px">
                <v-card>
                    <v-card-title>View Station</v-card-title>
                    <v-card-text>
                        <p>Station Code: {{ selectedStation.station_code }}</p>
                        <p>Station Name: {{ selectedStation.station_name }}</p>
                        <p>Added by: {{ selectedStation.user.full_name }}</p>
                    </v-card-text>
                </v-card>
            </v-dialog>
            <v-dialog v-model="dialog.delete" max-width="500px">
                <v-card>
                    <v-card-title style="background-color: green; color: white; text-transform: uppercase;">Delete Station</v-card-title>
                    <v-card-text>
                        <div>Are you sure you want to delete this station?</div>
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
const stations = ref(props.stations || []); // Ensure it's an array, defaulting to empty array if undefined

const dialog = ref({
    add: false,
    edit: false,
    view: false,
    delete: false,
});

const selectedStation = ref(null);
const newStation = ref({
    station_code: '',
    station_name: '',
    station_phone_number: '',
    address: ''
});

const headers = [
    { title: 'Station Code', value: 'station_code' },
    { title: 'Station Name', value: 'station_name' },
    { title: 'Phone Number', value: 'station_phone_number' },
    { title: 'Address', value: 'address' },
    { title: 'Actions', value: 'actions', sortable: false },
];

const page = ref(1);
const itemsPerPage = ref(7);
const search = ref('');

const filteredStations = computed(() => {
    const lowerSearch = search.value.toLowerCase();
    return stations.value.filter(station =>
        Object.values(station).some(value =>
            typeof value === 'string' && value.toLowerCase().includes(lowerSearch)
        )
    );
});

const paginatedStations = computed(() => {
    const start = (page.value - 1) * itemsPerPage.value;
    const end = start + itemsPerPage.value;
    return filteredStations.value.slice(start, end);
});

const pageCount = computed(() => {
    return Math.ceil(filteredStations.value.length / itemsPerPage.value);
});

const saveEdit = () => {
    console.log('Saving edit', selectedStation.value);
    Inertia.post('/stations/update', {
        id: selectedStation.value.id,
        station_code: selectedStation.value.station_code,
        station_name: selectedStation.value.station_name,
        station_phone_number: selectedStation.value.station_phone_number,
        address: selectedStation.value.address
    })
        .then(() => {
            console.log('Station details updated');
        })
        .catch(error => {
            console.error('Error updating station details:', error);
        });
    dialog.value.edit = false;
};

const confirmDelete = () => {
    console.log('Deleting station');
    Inertia.post('/stations/delete', { id: selectedStation.value.id })
        .then(() => {
            console.log('Station deleted');
            stations.value = stations.value.filter(station => station.id !== selectedStation.value.id);
        })
        .catch(error => {
            console.error('Error deleting station:', error);
        });
    dialog.value.delete = false;
};

const addStation = () => {
    console.log('Adding station', newStation.value);
    Inertia.post('/stations/create', newStation.value)
        .then(() => {
            console.log('Station added');
            stations.value.push(newStation.value);
            newStation.value = {
                station_code: '',
                station_name: '',
                station_phone_number: '',
                address: ''
            };
        })
        .catch(error => {
            console.error('Error adding station:', error);
        });
    dialog.value.add = false;
};

const importStations = () => {
    console.log('Importing stations');
};

const printStations = () => {
    console.log('Printing stations');
};

const exportStations = () => {
    console.log('Exporting stations');
};

const openEditDialog = (station) => {
    selectedStation.value = { ...station };
    dialog.value.edit = true;
};

const openViewDialog = (station) => {
    selectedStation.value = { ...station };
    dialog.value.view = true;
};

const openDeleteDialog = (station) => {
    selectedStation.value = { ...station };
    dialog.value.delete = true;
};
</script>
