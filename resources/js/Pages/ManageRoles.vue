<template>
    <AdminLayout>
        <v-col cols="12">
            <v-card max-width="1500" elevation="0">
                <v-card-title class="text-center" style="background-color: lightblue; color: black;">
                    Roles Management
                    <v-spacer></v-spacer>
                </v-card-title>
                <br>
                <v-card-text>
                    <v-btn color="primary" @click="importRoles" class="mr-4" label elevation="5" style="text-transform: capitalize;">
                        <v-icon left>mdi-upload</v-icon> Import
                    </v-btn>
                    <v-btn @click="printRoles" class="mr-4" label elevation="5" color="red" style="text-transform: capitalize;">
                        <v-icon left>mdi-printer</v-icon> Print
                    </v-btn>
                    <v-btn color="green" @click="exportRoles" class="mr-4" label elevation="5" style="text-transform: capitalize;">
                        <v-icon left>mdi-download</v-icon> Export
                    </v-btn>
                    <v-btn color="purple" @click="addRole" label elevation="5" style="text-transform: capitalize;">
                        <v-icon left>mdi-account-plus</v-icon> Add Role
                    </v-btn>
                </v-card-text>
                <v-divider></v-divider>
                <v-card-text elevation="0">
                    <!-- Data Table -->
                    <v-data-table
                        :headers="headers"
                        :items="roles"
                        item-key="id"
                        class="elevation-0"
                        dense
                        :search="search"
                    >
                        <template v-slot:column.actions="{ item }">
                            <v-btn icon @click="openEditDialog(item)">
                                <v-icon color="green">mdi-pencil</v-icon>
                            </v-btn>
                            <v-btn icon @click="openDeleteDialog(item)">
                                <v-icon color="red">mdi-delete</v-icon>
                            </v-btn>
                        </template>
                    </v-data-table>

                    <!-- Edit Dialog -->
                    <v-dialog v-model="dialog.edit" max-width="500px">
                        <v-card>
                            <v-card-title>Edit Role</v-card-title>
                            <v-card-text>
                                <v-form ref="form" v-model="formValid">
                                    <v-text-field
                                        v-model="selectedRole.name"
                                        label="Role Name"
                                        dense
                                    ></v-text-field>
                                    <v-text-field
                                        v-model="selectedRole.guard_name"
                                        label="Guard Name"
                                        dense
                                    ></v-text-field>
                                    <v-btn @click="saveEdit" color="green" style="text-transform: capitalize;">Save</v-btn>
                                    <v-btn @click="dialog.edit = false" color="red" style="text-transform: capitalize;">Cancel</v-btn>
                                </v-form>
                            </v-card-text>
                        </v-card>
                    </v-dialog>

                    <!-- Delete Dialog -->
                    <v-dialog v-model="dialog.delete" max-width="500px">
                        <v-card>
                            <v-card-title>Delete Role</v-card-title>
                            <v-card-text>
                                <div>Are you sure you want to delete "{{ selectedRole.name }}"?</div>
                                <v-btn @click="confirmDelete" color="green" style="text-transform: capitalize;">Confirm</v-btn>
                                <v-btn @click="dialog.delete = false" color="red" style="text-transform: capitalize;">Cancel</v-btn>
                            </v-card-text>
                        </v-card>
                    </v-dialog>
                </v-card-text>
            </v-card>
        </v-col>
    </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const { props } = usePage();
const roles = ref(props.roles || []); // Ensure it's an array, defaulting to empty array if undefined

// Dialog control
const dialog = ref({
    edit: false,
    delete: false,
});

// Selected role for editing or deleting
const selectedRole = ref(null);

// Headers for the data table
const headers = [
    { title: 'ID', value: 'id' },
    { title: 'Name', value: 'name' },
    { title: 'Guard Name', value: 'guard_name' },
    { title: 'Actions', value: 'actions', sortable: false },
];

// Search input for the data table
const search = ref('');

// Function to open the edit dialog and set the selected role
const openEditDialog = (role) => {
    selectedRole.value = { ...role };
    dialog.value.edit = true;
};

// Function to open the delete dialog and set the selected role
const openDeleteDialog = (role) => {
    selectedRole.value = { ...role };
    dialog.value.delete = true;
};

// Function to save the edited role
const saveEdit = () => {
    console.log('Saving edit:', selectedRole.value);
    // Implement your save logic here
    dialog.value.edit = false;
};

// Function to confirm deletion of a role
const confirmDelete = () => {
    console.log('Deleting role:', selectedRole.value);
    // Implement your delete logic here
    dialog.value.delete = false;
};

// Function to import roles
const importRoles = () => {
    console.log('Importing roles');
    // Implement your import logic here
};

// Function to print roles
const printRoles = () => {
    console.log('Printing roles');
    // Implement your print logic here
};

// Function to export roles
const exportRoles = () => {
    console.log('Exporting roles');
    // Implement your export logic here
};

// Function to add a new role
const addRole = () => {
    console.log('Adding a new role');
    // Implement your add role logic here
};
</script>

<style scoped>
/* Scoped styles for the table */
.v-data-table {
    width: 100%;
}
</style>
