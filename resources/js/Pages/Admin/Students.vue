<template>
    <AdminLayout>
        <v-col cols="12">
            <v-card elevation="0">
                <v-card-title class="text-center" style="background-color:lightblue; color:black;">
                    Student List
                    <v-spacer></v-spacer>
                </v-card-title>
                <br>
                <v-card-text>
                    <v-btn color="primary" @click="importStudents" class="mr-4" label elevation="5" style="text-transform: capitalize;">
                        <v-icon left>mdi-upload</v-icon> Import
                    </v-btn>
                    <v-btn @click="printStudents" class="mr-4" label elevation="5" color="red" style="text-transform: capitalize;">
                        <v-icon left>mdi-printer</v-icon> Print
                    </v-btn>
                    <v-btn color="green" @click="exportStudents" class="mr-4" label elevation="5" style="text-transform: capitalize;">
                        <v-icon left>mdi-download</v-icon> Export
                    </v-btn>
                    <v-btn color="blue" @click="dialog.add = true" label elevation="5" style="text-transform: capitalize;">
                        <v-icon left>mdi-plus</v-icon> Add Student
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
                    :items="paginatedStudents"
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
            <!-- Add Student Dialog -->
            <v-dialog v-model="dialog.add" max-width="600px">
                <v-card>
                    <v-toolbar color="white">
                        <v-card-title style="font-size:25px;font-weight: 900;">Add Student</v-card-title>
                        <v-spacer></v-spacer>
                        <v-btn @click="dialog.add = false" elevation="0" color="black" style="text-transform: capitalize;" icon><v-icon>mdi-close</v-icon></v-btn>
                    </v-toolbar>
                    <v-card-text>
                        <v-text-field
                            v-model="newStudent.student_number"
                            label="Student Number"
                            dense
                            variant="underlined"
                            prepend-inner-icon="mdi-tag"
                        ></v-text-field>
                        <v-text-field
                            v-model="newStudent.name"
                            label="Name"
                            dense
                            variant="underlined"
                            prepend-inner-icon="mdi-account"
                        ></v-text-field>
                        <v-text-field
                            v-model="newStudent.email"
                            label="Email"
                            dense
                            variant="underlined"
                            prepend-inner-icon="mdi-email"
                        ></v-text-field>
                        <v-text-field
                            v-model="newStudent.address"
                            label="Address"
                            dense
                            variant="underlined"
                            prepend-inner-icon="mdi-map-marker"
                        ></v-text-field>
                        <v-select
                            v-model="newStudent.course_id"
                            :items="courses"
                            item-value="id"
                            item-title="course_name"
                            label="Course"
                            dense
                            variant="underlined"
                            prepend-inner-icon="book"
                        ></v-select>
                        <v-btn
                            @click="addStudent"
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
                    <v-card-title>Edit Student</v-card-title>
                    <v-card-text>
                        <v-text-field v-model="selectedStudent.student_number" label="Student Number" dense></v-text-field>
                        <v-text-field v-model="selectedStudent.name" label="Name" dense></v-text-field>
                        <v-text-field v-model="selectedStudent.email" label="Email" dense></v-text-field>
                        <v-text-field v-model="selectedStudent.address" label="Address" dense></v-text-field>
                        <v-select
                            v-model="selectedStudent.course_id"
                            :items="courses"
                            item-value="id"
                            item-text="name"
                            label="Course"
                            dense
                        ></v-select>
                        <v-btn @click="saveEdit" elevation="3" class="mr-4" color="green" style="text-transform: capitalize;">Save</v-btn>
                        <v-btn @click="dialog.edit = false" elevation="3" color="red" style="text-transform: capitalize;">Cancel</v-btn>
                    </v-card-text>
                </v-card>
            </v-dialog>
            <v-dialog v-model="dialog.view" max-width="500px">
                <v-card>
                    <v-toolbar color="white">
                    <v-card-title style="font-weight: 900;">View Student</v-card-title>
                    </v-toolbar>
                    <v-card-text>
                        <p>Student Number: {{ selectedStudent.student_number }}</p>
                        <p>ID Number: {{ selectedStudent.ID_Number }}</p>
                        <p>Name: {{ selectedStudent.name }}</p>
                        <p>Email: {{ selectedStudent.email }}</p>
                        <p>Address: {{ selectedStudent.address }}</p>
                        <p>Course: {{ selectedStudent.course.course_name }}</p>
                        <br>
                        <em><p>Added by: {{ selectedStudent.user.full_name }}</p></em>
                    </v-card-text>
                </v-card>
            </v-dialog>
            <v-dialog v-model="dialog.delete" max-width="500px">
                <v-card>
                    <v-card-title style="background-color: green; color: white; text-transform: uppercase;">Delete Student</v-card-title>
                    <v-card-text>
                        <div>Are you sure you want to delete this student?</div>
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
const students = ref(props.students || []); // Ensure it's an array, defaulting to empty array if undefined
const courses = ref(props.courses || []); // Ensure courses are provided as well

const dialog = ref({
    add: false,
    edit: false,
    view: false,
    delete: false,
});

const selectedStudent = ref(null);
const newStudent = ref({
    student_number: '',
    name: '',
    email: '',
    address: '',
    course_id: ''
});

const headers = [
    { title: 'Student Number', value: 'student_number' },
    { title: 'ID Number', value: 'ID_Number' },
    { title: 'Name', value: 'name' },
    { title: 'Email', value: 'email' },
    { title: 'Address', value: 'address' },
    { title: 'Course', value: 'course.course_name' },
    { title: 'Added by', value: 'user.full_name' },
    { title: 'Actions', value: 'actions', sortable: false },
];

const page = ref(1);
const itemsPerPage = ref(7);
const search = ref('');

const filteredStudents = computed(() => {
    const lowerSearch = search.value.toLowerCase();
    return students.value.filter(student =>
        Object.values(student).some(value =>
            typeof value === 'string' && value.toLowerCase().includes(lowerSearch)
        )
    );
});

const paginatedStudents = computed(() => {
    const start = (page.value - 1) * itemsPerPage.value;
    const end = start + itemsPerPage.value;
    return filteredStudents.value.slice(start, end);
});

const pageCount = computed(() => {
    return Math.ceil(filteredStudents.value.length / itemsPerPage.value);
});

const saveEdit = () => {
    console.log('Saving edit', selectedStudent.value);
    Inertia.post('/students/update', {
        id: selectedStudent.value.id,
        student_number: selectedStudent.value.student_number,
        name: selectedStudent.value.name,
        email: selectedStudent.value.email,
        address: selectedStudent.value.address,
        course_id: selectedStudent.value.course_id
    }, {
        onSuccess: () => {
            dialog.value.edit = false;
        }
    });
};

const addStudent = () => {
    console.log('Adding student', newStudent.value);
    Inertia.post('/students', newStudent.value, {
        onSuccess: () => {
            dialog.value.add = false;
        }
    });
};

const openEditDialog = (student) => {
    selectedStudent.value = { ...student };
    dialog.value.edit = true;
};

const openViewDialog = (student) => {
    selectedStudent.value = student;
    dialog.value.view = true;
};

const openDeleteDialog = (student) => {
    selectedStudent.value = student;
    dialog.value.delete = true;
};

const confirmDelete = () => {
    console.log('Deleting student', selectedStudent.value);
    Inertia.delete(`/students/${selectedStudent.value.id}`, {
        onSuccess: () => {
            dialog.value.delete = false;
        }
    });
};

const importStudents = () => {
    console.log('Importing students');
    // Handle import functionality here
};

const printStudents = () => {
    console.log('Printing students');
    // Handle print functionality here
};

const exportStudents = () => {
    console.log('Exporting students');
    // Handle export functionality here
};
</script>
