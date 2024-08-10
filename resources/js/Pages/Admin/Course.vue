<template>
    <AdminLayout>
        <v-col cols="12">
            <v-card elevation="0">
                <v-card-title class="text-center" style="background-color:lightblue; color: black;">
                    Course List
                    <v-spacer></v-spacer>
                </v-card-title>
                <br>
                <v-card-text>
                    <v-btn color="primary" @click="importCourses" class="mr-4" label elevation="5" style="text-transform: capitalize;">
                        <v-icon left>mdi-upload</v-icon> Import
                    </v-btn>
                    <v-btn @click="printCourses" class="mr-4" label elevation="5" color="red" style="text-transform: capitalize;">
                        <v-icon left>mdi-printer</v-icon> Print
                    </v-btn>
                    <v-btn color="green" @click="exportCourses" class="mr-4" label elevation="5" style="text-transform: capitalize;">
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
                <!-- Data Table Container with Scroll -->
                <v-data-table
                    :headers="headers"
                    :items="paginatedCourses"
                    :search="search"
                    item-key="id"
                    class="elevation-1"
                >

                </v-data-table>

            </v-card>
            <!-- Edit Dialog -->
            <v-dialog v-model="dialog.edit" max-width="500px">
                <v-card>
                    <v-card-title>Edit Course</v-card-title>
                    <v-card-text>
                        <v-text-field v-model="selectedCourse.course_code" label="Course Code" dense></v-text-field>
                        <v-text-field v-model="selectedCourse.course_name" label="Course Name" dense></v-text-field>
                        <v-text-field v-model="selectedCourse.number_lessons" label="Number of Lessons" type="number" dense></v-text-field>
                        <v-text-field v-model="selectedCourse.amount" label="Amount" type="number" dense></v-text-field>
                        <v-btn @click="saveEdit" elevation="3" class="mr-4" color="green" style="text-transform: capitalize;">Save</v-btn>
                        <v-btn @click="dialog.edit = false" elevation="3" color="red" style="text-transform: capitalize;">Cancel</v-btn>
                    </v-card-text>
                </v-card>
            </v-dialog>
            <v-dialog v-model="dialog.view" max-width="500px">
                <v-card>
                    <v-card-title>View Course</v-card-title>
                    <v-card-text>
                        <!-- Details to be viewed here -->
                    </v-card-text>
                </v-card>
            </v-dialog>
            <v-dialog v-model="dialog.delete" max-width="500px">
                <v-card>
                    <v-card-title style="background-color: green; color: white; text-transform: uppercase;">Delete Course</v-card-title>
                    <v-card-text>
                        <div>Are you sure you want to delete this course?</div>
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
const courses = ref(props.courses || []); // Ensure it's an array, defaulting to empty array if undefined

const dialog = ref({
    edit: false,
    view: false,
    delete: false,
});

const selectedCourse = ref(null);

const headers = [
    { title: 'Course Code', value: 'course_code' },
    { title: 'Course Name', value: 'course_name' },
    { title: 'Added by', value: 'user.full_name' },
    { title: 'Number of Lessons', value: 'number_lessons' },
    { title: 'Amount', value: 'amount' },
    { title: 'Actions', value: 'actions', sortable: false },
];

const page = ref(1);
const itemsPerPage = ref(7);
const search = ref('');

const filteredCourses = computed(() => {
    const lowerSearch = search.value.toLowerCase();
    return courses.value.filter(course =>
        Object.values(course).some(value =>
            typeof value === 'string' && value.toLowerCase().includes(lowerSearch)
        )
    );
});

const paginatedCourses = computed(() => {
    const start = (page.value - 1) * itemsPerPage.value;
    const end = start + itemsPerPage.value;
    return filteredCourses.value.slice(start, end);
});

const pageCount = computed(() => {
    return Math.ceil(filteredCourses.value.length / itemsPerPage.value);
});

const saveEdit = () => {
    console.log('Saving edit', selectedCourse.value);
    Inertia.post('/courses/update', {
        id: selectedCourse.value.id,
        course_code: selectedCourse.value.course_code,
        course_name: selectedCourse.value.course_name,
        number_lessons: selectedCourse.value.number_lessons,
        amount: selectedCourse.value.amount,
    })
        .then(() => {
            console.log('Course details updated');
        })
        .catch(error => {
            console.error('Error updating course details:', error);
        });
    dialog.value.edit = false;
};

const confirmDelete = () => {
    console.log('Deleting course');
    Inertia.post('/courses/delete', { id: selectedCourse.value.id })
        .then(() => {
            console.log('Course deleted');
            courses.value = courses.value.filter(course => course.id !== selectedCourse.value.id);
        })
        .catch(error => {
            console.error('Error deleting course:', error);
        });
    dialog.value.delete = false;
};

const importCourses = () => {
    console.log('Importing courses');
};

const printCourses = () => {
    console.log('Printing courses');
};

const exportCourses = () => {
    console.log('Exporting courses');
};

const openEditDialog = (course) => {
    selectedCourse.value = { ...course };
    dialog.value.edit = true;
};

const openViewDialog = (course) => {
    selectedCourse.value = { ...course };
    dialog.value.view = true;
};

const openDeleteDialog = (course) => {
    selectedCourse.value = { ...course };
    dialog.value.delete = true;
};
</script>

<style scoped>
.table-container {
    max-height: 400px; /* Adjust the height as needed */
    overflow-y: auto;
}
</style>
