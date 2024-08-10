<template>
    <AdminLayout>
        <v-col cols="12">
            <v-card elevation="0">
                <v-card-title class="text-center" style="background-color:lightblue; color:black;">
                    Lesson List
                    <v-spacer></v-spacer>
                </v-card-title>
                <br>
                <v-card-text>
                    <v-btn color="primary" @click="importLessons" class="mr-4" label elevation="5" style="text-transform: capitalize;">
                        <v-icon left>mdi-upload</v-icon> Import
                    </v-btn>
                    <v-btn @click="printLessons" class="mr-4" label elevation="5" color="red" style="text-transform: capitalize;">
                        <v-icon left>mdi-printer</v-icon> Print
                    </v-btn>
                    <v-btn color="green" @click="exportLessons" class="mr-4" label elevation="5" style="text-transform: capitalize;">
                        <v-icon left>mdi-download</v-icon> Export
                    </v-btn>
                    <v-btn color="blue" @click="dialog.add = true" label elevation="5" style="text-transform: capitalize;">
                        <v-icon left>mdi-plus</v-icon> Add Lesson
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
                    :items="paginatedLessons"
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
            <!-- Add Lesson Dialog -->
            <v-dialog v-model="dialog.add" max-width="600px">
                <v-card>
                    <v-toolbar color="white">
                        <v-card-title style="font-size:25px;font-weight: 900;">Add Lesson</v-card-title>
                        <v-spacer></v-spacer>
                        <v-btn @click="dialog.add = false" elevation="0" color="black" style="text-transform: capitalize;" icon><v-icon>mdi-close</v-icon></v-btn>
                    </v-toolbar>
                    <v-card-text>
                        <v-select
                            v-model="newLesson.student_id"
                            :items="students"
                            item-value="id"
                            item-title="name"
                            label="Student"
                            dense
                            variant="underlined"
                        ></v-select>
                        <v-select
                            v-model="newLesson.instructor_id"
                            :items="instructors"
                            item-value="id"
                            item-title="name"
                            label="Instructor"
                            dense
                            variant="underlined"
                        ></v-select>
                        <v-select
                            v-model="newLesson.vehicle_id"
                            :items="vehicles"
                            item-value="id"
                            item-title="reg_no"
                            label="Vehicle"
                            dense
                            variant="underlined"
                        ></v-select>
                        <v-text-field
                            v-model="newLesson.datetime"
                            label="Date and Time"
                            type="datetime-local"
                            dense
                            variant="underlined"
                        ></v-text-field>
                        <v-btn
                            @click="addLesson"
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
                    <v-toolbar color="white">
                        <v-card-title style="font-size:25px;font-weight: 900;">Edit Lesson</v-card-title>
                        <v-spacer></v-spacer>
                        <v-btn @click="dialog.edit = false" elevation="0" color="black" style="text-transform: capitalize;" icon><v-icon>mdi-close</v-icon></v-btn>
                    </v-toolbar>
                    <v-card-text>
                        <v-select
                            v-model="selectedLesson.student_id"
                            :items="students"
                            item-value="id"
                            item-title="name"
                            label="Student"
                            dense
                            variant="underlined"
                        ></v-select>
                        <v-select
                            v-model="selectedLesson.instructor_id"
                            :items="instructors"
                            item-value="id"
                            item-title="name"
                            label="Instructor"
                            dense
                            variant="underlined"
                        ></v-select>
                        <v-select
                            v-model="selectedLesson.vehicle_id"
                            :items="vehicles"
                            item-value="id"
                            item-title="reg_no"
                            label="Vehicle"
                            dense
                            variant="underlined"
                        ></v-select>
                        <v-text-field
                            v-model="selectedLesson.datetime"
                            label="Date and Time"
                            type="datetime-local"
                            dense
                            variant="underlined"
                        ></v-text-field>
                        <v-btn @click="saveEdit" elevation="3" class="mr-4" color="green" style="text-transform: capitalize;">Save</v-btn>
                        <v-btn @click="dialog.edit = false" elevation="3" color="red" style="text-transform: capitalize;">Cancel</v-btn>
                    </v-card-text>
                </v-card>
            </v-dialog>
            <!-- View Dialog -->
            <v-dialog v-model="dialog.view" max-width="500px">
                <v-card>
                    <v-card-title>View Lesson</v-card-title>
                    <v-card-text>
                        <p>Student: {{ selectedLesson.student.name }}</p>
                        <p>Instructor: {{ selectedLesson.instructor.name }}</p>
                        <p>Vehicle: {{ selectedLesson.vehicle.reg_no }}</p>
                        <p>Date and Time: {{ selectedLesson.datetime }}</p>
                    </v-card-text>
                </v-card>
            </v-dialog>
            <!-- Delete Dialog -->
            <v-dialog v-model="dialog.delete" max-width="500px">
                <v-card>
                    <v-card-title style="background-color: green; color: white; text-transform: uppercase;">Delete Lesson</v-card-title>
                    <v-card-text>
                        <div>Are you sure you want to delete this lesson?</div>
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
const lessons = ref(props.lessons || []); // Ensure it's an array, defaulting to empty array if undefined
const students = ref(props.students || []);
const instructors = ref(props.instructors || []);
const vehicles = ref(props.vehicles || []);

const dialog = ref({
    add: false,
    edit: false,
    view: false,
    delete: false,
});

const selectedLesson = ref(null);
const newLesson = ref({
    student_id: '',
    instructor_id: '',
    vehicle_id: '',
    datetime: ''
});

const headers = [
    { title: 'Student', value: 'student.name' },
    { title: 'Instructor', value: 'instructor.name' },
    { title: 'Vehicle', value: 'vehicle.reg_no' },
    { title: 'Date and Time', value: 'datetime' },
    { title: 'Actions', value: 'actions', sortable: false },
];

const page = ref(1);
const itemsPerPage = ref(7);
const search = ref('');

const filteredLessons = computed(() => {
    const lowerSearch = search.value.toLowerCase();
    return lessons.value.filter(lesson =>
        Object.values(lesson).some(value =>
            typeof value === 'string' && value.toLowerCase().includes(lowerSearch)
        )
    );
});

const paginatedLessons = computed(() => {
    const start = (page.value - 1) * itemsPerPage.value;
    const end = start + itemsPerPage.value;
    return filteredLessons.value.slice(start, end);
});

const pageCount = computed(() => {
    return Math.ceil(filteredLessons.value.length / itemsPerPage.value);
});

const saveEdit = () => {
    console.log('Saving edit', selectedLesson.value);
    dialog.value.edit = false;
    // TODO: Implement save logic
};

const addLesson = () => {
    console.log('Adding lesson', newLesson.value);
    // TODO: Implement add logic
};

const openEditDialog = (lesson) => {
    selectedLesson.value = { ...lesson };
    dialog.value.edit = true;
};

const openViewDialog = (lesson) => {
    selectedLesson.value = { ...lesson };
    dialog.value.view = true;
};

const openDeleteDialog = (lesson) => {
    selectedLesson.value = { ...lesson };
    dialog.value.delete = true;
};

const confirmDelete = () => {
    console.log('Deleting lesson', selectedLesson.value);
    dialog.value.delete = false;
    // TODO: Implement delete logic
};

const importLessons = () => {
    console.log('Importing lessons');
    // TODO: Implement import logic
};

const printLessons = () => {
    console.log('Printing lessons');
    // TODO: Implement print logic
};

const exportLessons = () => {
    console.log('Exporting lessons');
    // TODO: Implement export logic
};
</script>

<style scoped>
/* Add your styles here */
</style>
