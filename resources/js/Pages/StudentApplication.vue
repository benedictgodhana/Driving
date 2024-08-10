<template>
    <Head title="New Application" />
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
            <v-card class="pa-6" elevation="0">
              <v-card-title style="background-color: green; color: white">Application Form</v-card-title>
              <br>
              <v-card-text>
                <v-form ref="form" v-model="isFormValid" @submit.prevent="submitForm">
                  <v-row>
                    <v-col cols="12" md="4">
                      <v-select
                        v-model="application.location"
                        :items="locations"
                        item-value="id"
                        item-title="name"
                        label="Location"
                        :rules="[rules.required]"
                        variant="underlined"
                        prepend-inner-icon="mdi-map-marker"
                        @change="updateInstitutions"
                      ></v-select>
                    </v-col>

                    <v-col cols="12" md="4">
                      <v-select
                        v-model="application.applicationType"
                        :items="applicationTypes"
                        item-value="id"
                        item-title="name"
                        label="Application Type"
                        :rules="[rules.required]"
                        variant="underlined"
                        prepend-inner-icon="mdi-clipboard-list"
                        @change="updateActivities"
                      ></v-select>
                    </v-col>

                    <v-col cols="12" md="4">
                      <v-select
                        v-model="application.institution"
                        :items="filteredInstitutions"
                        item-value="id"
                        item-title="name"
                        label="Institution"
                        :rules="[rules.required]"
                        variant="underlined"
                        prepend-inner-icon="mdi-school"
                      ></v-select>
                    </v-col>

                    <v-col cols="12" md="4">
                      <v-select
                        v-model="application.activity"
                        :items="activities"
                        item-value="id"
                        item-title="name"
                        label="Activity"
                        :rules="[rules.required]"
                        variant="underlined"
                        prepend-inner-icon="mdi-pencil"
                      ></v-select>
                    </v-col>

                    <v-col cols="12" md=4>
                      <v-text-field
                        v-model="application.preferredStartDate"
                        label="Preferred Start Date"
                        type="date"
                        :rules="[rules.required]"
                        variant="underlined"
                        prepend-inner-icon="mdi-calendar"
                      ></v-text-field>
                    </v-col>
                    <v-col cols="12" md="4">
                      <v-file-input
                        v-model="application.cover_letter"
                        label="Upload Resume"
                        :rules="[rules.required]"
                        variant="underlined"
                        prepend-inner-icon="mdi-file"
                      ></v-file-input>
                    </v-col>

                    <v-col cols="12">
                      <v-textarea
                        v-model="application.description"
                        label="Description"
                        :rules="[rules.required]"
                        variant="outlined"
                        prepend-inner-icon="mdi-file-document-outline"
                        rows="4"
                      ></v-textarea>
                    </v-col>


                  </v-row>
                  <div class="flex items-center gap-4 mt-6">
                    <v-btn type="submit" :loading="isSubmitting" color="orange" style="color: white; text-transform: capitalize;">
                      Submit <v-icon size="16" left>mdi-send</v-icon>
                    </v-btn>
                    <v-btn @click="resetForm" color="grey" style="color: white; text-transform: capitalize;">
                      Reset <v-icon size="16" left>mdi-refresh</v-icon>
                    </v-btn>
                  </div>
                </v-form>
              </v-card-text>
            </v-card>
          </v-col>
        </v-row>
      </v-container>

      <v-dialog v-model="dialog" max-width="600px">
        <v-card>
          <v-toolbar style="background-color:darkblue;color:white ;">
            <v-toolbar-title>Contribution Details</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-tooltip top>
              <template v-slot:activator="{ on, attrs }">
                <v-btn icon dark v-bind="attrs" v-on="on" @click="dialog = false" style="background-color:white;">
                  <v-icon color="black">mdi-close</v-icon>
                </v-btn>
              </template>
              <span>Close</span>
            </v-tooltip>
          </v-toolbar>
          <v-card-text>
            <p><strong>Date:</strong> {{ formatDate(selectedItem.date) }}</p>
            <br>
            <hr>
            <p class="mt-3"><strong>Description:</strong> {{ selectedItem.description }}</p>
            <br>
            <hr>
            <p class="mt-3"><strong>Amount:</strong> {{ selectedItem.amount }}</p>
            <br>
            <hr>
            <p class="mt-3"><strong>Transaction Code:</strong> {{ selectedItem.code }}</p>
            <br>
            <hr class="mt-3">
            <v-btn style="background-color:darkblue;color:white;text-transform:capitalize;" class="mt-6" width="100%" @click="dialog = false">Close</v-btn>
          </v-card-text>
        </v-card>
      </v-dialog>
    </AuthenticatedLayout>
  </template>

  <script setup>
  import { ref, reactive, computed } from 'vue';
  import { usePage, Head, router } from '@inertiajs/vue3';
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
  import NavLink from '@/Components/NavLink.vue';
  import { Inertia } from '@inertiajs/inertia';

  const { props } = usePage();
  const selectedItem = ref(props.selectedItem || {});

  const application = reactive({
    applicationType:'',
    description: null,
    preferredStartDate: null,
    cover_letter: null,
    location: null,
    institution: null,
    activity: null,
  });


const links = [
    { text: 'My Dashboard', routeName: 'dashboard', icon: 'mdi-account' },
    { text: 'New Application', routeName: 'new_application', icon: 'mdi-file' },
    { text: 'Notifications', routeName: 'notifications', icon: 'mdi-bell' },
    { text: 'My Settings', routeName: 'settings', icon: 'mdi-cog' },
  ];

  const isFormValid = ref(false);
  const isSubmitting = ref(false);

  const locations = ref(props.locations || []);
  const institutions = ref(props.institutions || []);
  const applicationTypes = ref(props.applicationTypes || []);
  const activities = ref(props.activities || []);

  const filteredInstitutions = computed(() => {
    if (!application.location || !application.applicationType) {
      return [];
    }

    return institutions.value.filter(institution => {
      const isLocationMatch = institution.location_id === application.location;
      const isApplicationTypeMatch = institution.application_types.some(type => type.id === application.applicationType);
      return isLocationMatch && isApplicationTypeMatch;
    });
  });

  const rules = {
    required: value => !!value || 'Required.',
  };

  const updateInstitutions = location => {
    application.location = location;
  };

  const updateActivities = applicationType => {
    application.applicationType = applicationType;
  };

  const submitForm = () => {
  // Ensure that formData is properly constructed
  const formData = new FormData();
  formData.append('applicationType', application.applicationType);
  formData.append('description', application.description);
  formData.append('preferredStartDate', application.preferredStartDate);
  formData.append('cover_letter', application.cover_letter);
  formData.append('location', application.location);
  formData.append('institution', application.institution);
  formData.append('activity', application.activity);

  isSubmitting.value = true;

  // Use Inertia.post to handle form submission
  Inertia.post('/applications', formData, {
    onFinish: () => {
      isSubmitting.value = false;
      resetForm();
    },
    onError: () => {
      isSubmitting.value = false;
    }
  });
};


  const resetForm = () => {
    application.applicationType = '';
    application.description = null;
    application.preferredStartDate = null;
    application.cover_letter = null;
    application.location = null;
    application.institution = null;
    application.activity = null;
  };

  const dialog = ref(false);

  const formatDate = date => {
    if (!date) return '';
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(date).toLocaleDateString(undefined, options);
  };
  </script>

  <style>
  .profile-card {
    border: 1px solid #e0e0e0;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  }
  </style>
