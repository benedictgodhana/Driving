<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout>
      <v-container width="100%" style="max-width: 1600px;">
        <v-row>
          <!-- Profile Card Section -->
          <v-col cols="12" md="3" class="mb-4 ">
  <v-card class="profile-card" elevation="0" style="border: 1px solid #e0e0e0; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
    <v-card-text class="text-center py-4">
      <v-avatar size="200">
        <img src="/Images/male-avatar-icon.png" alt="User Avatar" height="250">
      </v-avatar>
      <v-card-text style="font-weight: 800;">
        {{ $page.props.auth.user.name }}
      </v-card-text>
      <v-divider></v-divider>
      <div class="mt-3">
        <v-divider></v-divider>
        <v-list class="mt-10" nav>
          <v-list-item v-for="(item, i) in links" :key="i">
            <NavLink :href="item.routeName" class="v-list-item" style="color: black;">
              <template v-slot:default="{ href, isActive, isExactActive, isLink }">
                <v-list-item-icon v-if="item.icon" class="list-item-icon">
                  <v-icon :icon="item.icon" style="color:green"></v-icon>
                </v-list-item-icon>
                <v-list-item-content>
                  <v-list-item-title :class="{ 'primary--text': isExactActive }" v-text="item.text"></v-list-item-title>
                </v-list-item-content>
              </template>
            </NavLink>
          </v-list-item>
        </v-list>
      </div>
    </v-card-text>
  </v-card>
</v-col>


          <!-- Form Section -->
          <v-col cols="12" md="9">
            <v-card >
    <v-tabs
      v-model="tab"
      style="background-color: green;color:white"
    >
    <v-tab value="one" style="text-transform: capitalize">ApplicationInformation</v-tab>
      <v-tab value="two" style="text-transform: capitalize">Personal Details</v-tab>


    </v-tabs>

    <v-card-text>
      <v-tabs-window v-model="tab">
        <v-tabs-window-item value="one">
          <v-card class="pa-6" elevation="0" >
    <v-card-title style="background-color:green; color: white">Application Details</v-card-title>
    <br>
    <v-card-text>
      <v-table class="application-table">
        <thead>
          <tr style="background-color: green;font-weight: 900;text-transform: uppercase;color:white" >
            <th>Status</th>
            <th>Location</th>
            <th>Application Type</th>
            <th>Institution</th>
            <th>Description</th>
            <th>Activity</th>
            <th>Created At</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(detail, index) in applications" :key="index">
            <td>{{ detail.status }}</td>
            <td>{{ detail.location.name }}</td>
            <td>{{ detail.application_type.name }}</td>
            <td>{{ detail.institution.name }}</td>
            <td>{{ detail.description }}</td>
            <td>{{ detail.activity.name}}</td>
            <td>{{ formatDate(detail.created_at) }}</td>
            <td>
              <v-btn icon variant="tonal"   @click="viewApplication(detail)" color="green">
                <v-icon>mdi-eye</v-icon>
              </v-btn>
            </td>
          </tr>
        </tbody>
      </v-table>
    </v-card-text>
  </v-card>

</v-tabs-window-item>



<v-tabs-window-item value="two">
  <v-card class="pa-6" elevation="0">
    <v-card-title style="background-color:green; color:white">Personal Information</v-card-title>

    <v-card-text class="mt-2">
      <v-form>
        <v-row>
          <v-col cols="12" md="4">
            <v-text-field
              label="Name"
              v-model="user.name"
              readonly
              variant="underlined"
              prepend-inner-icon="mdi-account"
            ></v-text-field>
          </v-col>
          <v-col cols="12" md="4">
            <v-text-field
              label="Username"
              v-model="user.username"
              readonly
              variant="underlined"
              prepend-inner-icon="mdi-account"
            ></v-text-field>
          </v-col>
          <v-col cols="12" md="4">
            <v-text-field
              label="Admission Number"
              v-model="user.admission_number"
              readonly
              variant="underlined"
              prepend-inner-icon="mdi-account"
            ></v-text-field>
          </v-col>
        </v-row>
        <v-row>
          <v-col cols="12" md="4">
            <v-text-field
              label="Email"
              type="email"
              v-model="user.email"
              readonly
              variant="underlined"
              prepend-inner-icon="mdi-email"
            ></v-text-field>
          </v-col>


          <v-col cols="12" md="4">
            <v-text-field
              label="Faculty"
              v-model="profile.faculty"
              readonly
              variant="underlined"
              prepend-inner-icon="mdi-office-building"
            ></v-text-field>
          </v-col>
          <v-col cols="12" md="4">
            <v-text-field
              label="Course Title"
              v-model="profile.course"
              readonly
              variant="underlined"
              prepend-inner-icon="mdi-briefcase"
            ></v-text-field>
          </v-col>
          <v-col cols="12" md="4">
            <v-text-field
              label="Year of Study"
              v-model="profile.year_of_study"
              readonly
              variant="underlined"
              prepend-inner-icon="mdi-book"
            ></v-text-field>
          </v-col>


          <v-col cols="12" md="4">
            <v-text-field
              label="Gender"
              v-model="profile.gender"
              readonly
              variant="underlined"
              prepend-inner-icon="mdi-gender-male-female"
            ></v-text-field>
          </v-col>
          <v-col cols="12" md="4">
            <v-text-field
              label="Religion"
              v-model="profile.religion"
              readonly
              variant="underlined"
              prepend-inner-icon="mdi-cross"
            ></v-text-field>
          </v-col>
        </v-row>
        <v-row>
          <v-col cols="12" md="4">
            <v-text-field
              label="Telephone (Residence)"
              v-model="profile.address"
              readonly
              variant="underlined"
              prepend-inner-icon="mdi-phone-outline"
            ></v-text-field>
          </v-col>
          <v-col cols="12" md="4">
            <v-text-field
              label="Cell Phone"
              v-model="profile.phone_number"
              readonly
              variant="underlined"
              prepend-inner-icon="mdi-cellphone-basic"
            ></v-text-field>
          </v-col>


        </v-row>
        <v-row>
            <v-col >
            <v-textarea
              label="Bio"
              v-model="profile.bio"
              type="date"
              readonly
              variant="underlined"
              prepend-inner-icon="mdi-history"
            ></v-textarea>
          </v-col>
        </v-row>
        <div class="flex items-center gap-4 mt-6">
          <v-btn :loading="user.processing" type="submit" width="100%" style="background-color: orange; color:white; text-transform: capitalize;">
            Save <v-icon size="16" left>mdi-content-save</v-icon>
          </v-btn>
        </div>
      </v-form>
    </v-card-text>
  </v-card>
</v-tabs-window-item>



      </v-tabs-window>
    </v-card-text>
  </v-card>
          </v-col>
        </v-row>
      </v-container>

      <!-- Other sections of your dashboard -->
      <!-- ... -->

      <!-- View Details Dialog -->
      <v-dialog v-model="dialog" max-width="600px">
        <v-card>
          <v-toolbar style="background-color: green; color: white;">
            <v-toolbar-title>Contribution Details</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-tooltip top>
              <template v-slot:activator="{ on, attrs }">
                <v-btn icon dark v-bind="attrs" v-on="on" @click="dialog = false" style="background-color: white;">
                  <v-icon color="black">mdi-close</v-icon>
                </v-btn>
              </template>
              <span>Close</span>
            </v-tooltip>
          </v-toolbar>
          <v-card-text>
            <p><strong>Date:</strong> {{ formatDate(selectedItem.date) }}</p>
            <hr>
            <p><strong>Description:</strong> {{ selectedItem.description }}</p>
            <hr>
            <p><strong>Amount:</strong> {{ selectedItem.amount }}</p>
            <hr>
            <p><strong>Transaction Code:</strong> {{ selectedItem.code }}</p>
            <v-btn color="primary" class="mt-6" @click="dialog = false">Close</v-btn>
          </v-card-text>
        </v-card>
      </v-dialog>


      <v-dialog v-model="showDialog" max-width="800px">
    <v-card>
      <v-toolbar style="background-color: green; color: white;">
        <v-toolbar-title>Application Details</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-btn icon @click="showDialog = false" color="white">
          <v-icon>mdi-close</v-icon>
        </v-btn>
      </v-toolbar>
      <v-card-text>
        <v-row>
          <v-col cols="12" md="4">
            <v-text-field
              label="Status"
              v-model="selectedApplication.status"
              readonly
              variant="solo"
              prepend-inner-icon="mdi-information-outline"
            ></v-text-field>
          </v-col>
          <v-col cols="12" md="4">
            <v-text-field
              label="Application Type"
              v-model="selectedApplication.application_type.name"
              readonly
              variant="solo"
              prepend-inner-icon="mdi-clipboard-list"
            ></v-text-field>
          </v-col>

          <v-col cols="12" md="4">
            <p>Created At: {{ formatDate(selectedApplication.created_at) }}</p>
          </v-col>

          <v-col >
            <v-textarea
              label="Description"
              v-model="selectedApplication.description"
              readonly
              variant="solo"
              prepend-inner-icon="mdi-file-document-outline"
            ></v-textarea>
          </v-col>
        </v-row>
      </v-card-text>
    </v-card>
  </v-dialog>
    </AuthenticatedLayout>
  </template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Pie } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, ArcElement, CategoryScale } from 'chart.js';
import AccessControl from '@/Components/AccessControl.vue';
import NavLink from '@/Components/NavLink.vue';

import axiosInstance from '../plugins/api';



const links = [
    { text: 'My Dashboard', routeName: 'dashboard', icon: 'mdi-account' },
    { text: 'New Application', routeName: 'new_application', icon: 'mdi-file' },
    { text: 'Notifications', routeName: 'notifications', icon: 'mdi-bell' },
    { text: 'My Settings', routeName: 'settings', icon: 'mdi-cog' },
  ];


  const props = defineProps({
  applications: {
    type: Array,
    default: () => []
  }
});

// Reactive variables
const applications = ref(props.applications);

const showDialog = ref(false);
const selectedApplication = ref({});

const viewApplication = (application) => {
  selectedApplication.value = application;
  showDialog.value = true;
};
const search = ref('');
const selectedFilter = ref(null);
const filters = ['All', 'Option 1', 'Option 2'];
const tab =ref(0)
const user = usePage().props.auth.user;
const profile = usePage().props.auth.user.profile;

const userProfile = user ? user.userProfile || {} : {};
const contributions = user ? user.contributions || [] : [];

const items = ref(contributions);

const filteredItems = computed(() => {
  return items.value.filter((item) => {
    const matchesFilter = !selectedFilter.value || selectedFilter.value === 'All' || item.description.includes(selectedFilter.value);
    const matchesSearch = !search.value || item.description.toLowerCase().includes(search.value.toLowerCase());
    return matchesFilter && matchesSearch;
  });
});

const formatDate = (dateString) => {
  const date = new Date(dateString);

  const optionsDate = {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  };

  const optionsTime = {
    hour: '2-digit',
    minute: '2-digit',
    second: '2-digit',
    hour12: true
  };

  const formattedDate = date.toLocaleDateString('en-US', optionsDate);
  const formattedTime = date.toLocaleTimeString('en-US', optionsTime);

  return `${formattedDate} at ${formattedTime}`;
};


const userInitials = computed(() => {
  const nameParts = user.name.split(' ');
  if (nameParts.length >= 2) {
    const firstNameInitial = nameParts[0].charAt(0).toUpperCase();
    const lastNameInitial = nameParts[1].charAt(0).toUpperCase();
    return firstNameInitial + lastNameInitial;
  } else {
    return user.name.charAt(0).toUpperCase() + (user.name.charAt(1) || '').toUpperCase();
  }
});

const downloadCSV = () => {
  const csvContent = 'data:text/csv;charset=utf-8,' + items.value.map(e => Object.values(e).join(',')).join('\n');
  const encodedUri = encodeURI(csvContent);
  const link = document.createElement('a');
  link.setAttribute('href', encodedUri);
  link.setAttribute('download', 'table_data.csv');
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
};

const totalAmount = computed(() => {
  return items.value.reduce((sum, item) => sum + parseFloat(item.amount.replace(/[^0-9.-]+/g,"")), 0).toFixed(0);
});

const dialog = ref(false);
const selectedItem = ref({});

const viewDetails = (item) => {
  selectedItem.value = item;
  dialog.value = true;
};
</script>


  <style scoped>
  .profile-card {
    background-color: white;
    padding: 16px;
    border-radius: 8px;
  }

  .total-amount {
    font-size: 24px;
    font-weight: bold;
    color: green;
  }

  .recent-activities-title {
    margin-top: 12px;
    font-size: 16px;
    color: gray;
  }

  .form-card {
    margin-top: 20px;
  }

  .application-title {
  background-color: green;
  color: white;
}

.application-table {
  width: 100%;
  border-collapse: collapse;
}

.application-table th {
  padding: 12px 15px;
  text-align: left;
  font-weight: bold;
  border-bottom: 1px solid #ddd;
}

.application-table td {
  padding: 12px 15px;
  text-align: left;
  border-bottom: 1px solid #ddd;
}

.application-table tr:nth-child(even) {
  background-color: #f9f9f9;
}

.application-table tr:hover {
  background-color: #f1f1f1;
}

.application-table th:first-child,
.application-table td:first-child {
  padding-left: 20px;
}

.application-table th:last-child,
.application-table td:last-child {
  padding-right: 20px;
}
  </style>
