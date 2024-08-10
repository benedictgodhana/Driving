<template>
    <AdminLayout>
        <v-col cols="12">
            <v-card elevation="0">
                <v-card-title class="text-center" style="background-color:green; color: white;">
                    Applications List
                    <v-spacer></v-spacer>
                </v-card-title>
                <br>
                <v-card-text>
                    <v-btn color="primary" @click="importApplications" class="mr-4" label elevation="5" style="text-transform: capitalize;">
                        <v-icon left>mdi-upload</v-icon> Import
                    </v-btn>
                    <v-btn @click="printApplications" class="mr-4" label elevation="5" color="red" style="text-transform: capitalize;">
                        <v-icon left>mdi-printer</v-icon> Print
                    </v-btn>
                    <v-btn color="green" @click="exportApplications" class="mr-4" label elevation="5" style="text-transform: capitalize;">
                        <v-icon left>mdi-download</v-icon> Export
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
                <!-- Table Container with Scroll -->
                <div class="table-container">
                    <v-table>
                        <thead>
                            <tr style="background-color: green;font-weight: 900;text-transform: uppercase;color:white">
                                <th>Date</th>
                                <th>Member Name</th>
                                <th>Application Type</th>
                                <th>Location</th>
                                <th>Institution</th>
                                <th>Activity</th>
                                <th>Preferred Start Date</th>
                                <th>Description</th>
                                <th>Cover Letter</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="application in paginatedApplications" :key="application.id">
                                <td>{{ new Date(application.created_at).toLocaleDateString() }}</td>
                                <td>{{ application.user.name }}</td>
                                <td>{{ application.application_type.name }}</td>
                                <td>{{ application.location.name }}</td>
                                <td>{{ application.institution.name }}</td>
                                <td>{{ application.activity.name }}</td>
                                <td>{{ application.preferred_start_date }}</td>
                                <td>{{ application.description }}</td>
                                <td>{{ application.cover_letter }}</td>
                                <td>{{ application.status }}</td>
                                <td>
                                    <v-btn icon color="white" @click="openEditDialog(application)" elevation="0">
                                        <v-icon color="green">mdi-pencil</v-icon>
                                    </v-btn>
                                    <v-btn icon color="white" @click="openViewDialog(application)" elevation="0">
                                        <v-icon color="primary">mdi-eye</v-icon>
                                    </v-btn>
                                    <v-btn icon color="white" @click="openDeleteDialog(application)" elevation="0">
                                        <v-icon color="red">mdi-delete</v-icon>
                                    </v-btn>
                                    <v-btn icon color="white" @click="approveApplication(application.id)" elevation="0">
                                        <v-icon color="blue">mdi-check</v-icon>
                                    </v-btn>
                                </td>
                            </tr>
                        </tbody>
                    </v-table>
                </div>
                <v-pagination
                    v-model:page="page"
                    :length="pageCount"
                    circle
                    class="mt-4"
                ></v-pagination>
            </v-card>
            <!-- Edit Dialog -->
            <v-dialog v-model="dialog.edit" max-width="500px">
                <v-card>
                    <v-card-title>Edit Application</v-card-title>
                    <v-card-text>
                        <v-select
                            v-model="selectedApplication.value.status"
                            :items="statusOptions"
                            label="Status"
                            dense
                        ></v-select>
                        <v-btn @click="saveEdit" elevation="3" class="mr-4" color="green" style="text-transform: capitalize;">Save</v-btn>
                        <v-btn @click="dialog.edit = false" elevation="3" color="red" style="text-transform: capitalize;">Cancel</v-btn>
                    </v-card-text>
                </v-card>
            </v-dialog>
            <v-dialog v-model="dialog.view" max-width="500px">
                <v-card>
                    <v-card-title>View Application</v-card-title>
                    <v-card-text>
                        <!-- Details to be viewed here -->
                    </v-card-text>
                </v-card>
            </v-dialog>
            <v-dialog v-model="dialog.delete" max-width="500px">
                <v-card>
                    <v-card-title style="background-color: green;color:white;text-transform: uppercase">Delete Application</v-card-title>
                    <v-card-text>
                        <div>Are you sure you want to delete this application?</div>
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
const applications = ref(props.applications);

const dialog = ref({
    edit: false,
    view: false,
    delete: false,
});

const selectedApplication = ref(null);
const statusOptions = ref(['Pending', 'Approved', 'Rejected']); // Example status options

const page = ref(1);
const itemsPerPage = ref(7);
const search = ref('');

const filteredApplications = computed(() => {
    const lowerSearch = search.value.toLowerCase();
    return applications.value.filter(application =>
        Object.values(application).some(value =>
            typeof value === 'string' && value.toLowerCase().includes(lowerSearch)
        )
    );
});

const paginatedApplications = computed(() => {
    const start = (page.value - 1) * itemsPerPage.value;
    const end = start + itemsPerPage.value;
    return filteredApplications.value.slice(start, end);
});

const pageCount = computed(() => {
    return Math.ceil(filteredApplications.value.length / itemsPerPage.value);
});

const saveEdit = () => {
    console.log('Saving edit', selectedApplication.value);
    Inertia.post('/applications/update', {
        id: selectedApplication.value.id,
        status: selectedApplication.value.status,
    })
        .then(() => {
            console.log('Application status updated');
        })
        .catch(error => {
            console.error('Error updating application status:', error);
        });
    dialog.value.edit = false;
};

const confirmDelete = () => {
    console.log('Deleting application');
    dialog.value.delete = false;
};

const importApplications = () => {
    console.log('Importing applications');
};

const printApplications = () => {
    console.log('Printing applications');
};

const exportApplications = () => {
    console.log('Exporting applications');
};

const openEditDialog = (application) => {
    selectedApplication.value = { ...application };
    dialog.value.edit = true;
};

const openViewDialog = (application) => {
    selectedApplication.value = { ...application };
    dialog.value.view = true;
};

const openDeleteDialog = (application) => {
    selectedApplication.value = { ...application };
    dialog.value.delete = true;
};

const approveApplication = (applicationId) => {
    Inertia.post('/applications/approve', { id: applicationId })
        .then(() => {
            console.log('Application approved');
            const application = applications.value.find(app => app.id === applicationId);
            if (application) {
                application.status = 'Approved';
            }
        })
        .catch(error => {
            console.error('Error approving application:', error);
        });
};
</script>

<style scoped>
.table-container {
    max-height: 400px; /* Adjust the height as needed */
    overflow-y: auto;
}
</style>
