<script setup>
import { ref, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  username: '',
  admission_number: '',
  address: '',
  phone_number: '',
  course: '',
  year_of_study: '',
  faculty: '',
  bio: '',
  gender: '',
  religion: '',
});

const step = ref(1);

// Computed properties to check if all required fields in the current step are filled
const isStep1Valid = computed(() => {
  return form.name && form.email && form.username && form.admission_number;
});

const isStep2Valid = computed(() => {
  return form.course && form.year_of_study && form.faculty && form.bio && form.gender && form.religion;
});

const isStep3Valid = computed(() => {
  return form.address && form.phone_number && form.password && form.password_confirmation && form.password === form.password_confirmation;
});

const nextStep = () => {
  if (step.value === 1 && isStep1Valid.value) {
    step.value += 1;
  } else if (step.value === 2 && isStep2Valid.value) {
    step.value += 1;
  }
};

const prevStep = () => {
  step.value -= 1;
};

const submit = () => {
  if (isStep3Valid.value) {
    form.post(route('register'));
  }
};
</script>

<template>
  <v-container>
    <v-card style="margin-top:90px" elevation="0">
      <br>

      <v-img
        src="/Images/360_F_309742761_GPrVMq6nriNIAPgWJebFdg4dvO6MBIhI-removebg-preview.png"
        contain
        max-width="200"
        class="mx-auto mt-4"
      ></v-img>
      <v-card-text>
        <template v-if="step === 1">
          <v-row>
            <v-col cols="12" md="6">
              <v-text-field
                label="Name"
                v-model="form.name"
                required
                variant="underlined"
                :rules="[v => !!v || 'Name is required']"
                prepend-icon="mdi-account"
              ></v-text-field>
            </v-col>
            <v-col cols="12" md="6">
              <v-text-field
                label="Email"
                v-model="form.email"
                required
                variant="underlined"
                :rules="[v => !!v || 'Email is required', v => /.+@.+\..+/.test(v) || 'E-mail must be valid']"
                prepend-icon="mdi-email"
              ></v-text-field>
            </v-col>
            <v-col cols="12" md="6">
              <v-text-field
                label="Username"
                v-model="form.username"
                required
                variant="underlined"
                prepend-icon="mdi-account"
              ></v-text-field>
            </v-col>
            <v-col cols="12" md="6">
              <v-text-field
                label="Admission Number"
                v-model="form.admission_number"
                required
                variant="underlined"
                prepend-icon="mdi-numeric"
              ></v-text-field>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12">
              <v-btn
                @click="nextStep"
                width="100%"
                color="orange"
                :disabled="!isStep1Valid"
                style="text-transform: capitalize"
              >
                Next
              </v-btn>
            </v-col>
          </v-row>
        </template>

        <template v-if="step === 2">
          <v-row>
            <v-col cols="12" md="6">
              <v-select
                :items="['Computer Science', 'Information Technology', 'Business Administration']"
                label="Course"
                v-model="form.course"
                required
                variant="underlined"
                prepend-icon="mdi-book"
              ></v-select>
            </v-col>
            <v-col cols="12" md="6">
              <v-text-field
                label="Year of Study"
                v-model="form.year_of_study"
                required
                variant="underlined"
                prepend-icon="mdi-calendar"
              ></v-text-field>
            </v-col>
            <v-col cols="12" md="6">
              <v-select
                :items="['Faculty of Computing', 'Faculty of Business', 'Faculty of Arts']"
                label="Faculty"
                v-model="form.faculty"
                required
                variant="underlined"
                prepend-icon="mdi-school"
              ></v-select>
            </v-col>
            <v-col cols="12" md="6">
              <v-text-field
                label="Bio"
                v-model="form.bio"
                required
                variant="underlined"
                prepend-icon="mdi-information"
              ></v-text-field>
            </v-col>
            <v-col cols="12" md="6">
              <v-select
                :items="['Male', 'Female']"
                label="Gender"
                v-model="form.gender"
                required
                variant="underlined"
                prepend-icon="mdi-gender-male-female"
              ></v-select>
            </v-col>
            <v-col cols="12" md="6">
              <v-select
                :items="['Catholic', 'Protestant', 'Other','Muslim  ']"
                label="Religion"
                v-model="form.religion"
                required
                variant="underlined"
                prepend-icon="mdi-church"
              ></v-select>
            </v-col>
          </v-row>
          <v-row>
            <v-col>
              <v-btn
                @click="prevStep"
                width="100%"
                color="grey"
                style="text-transform: capitalize"
              >
                Previous
              </v-btn>
            </v-col>
            <v-col>
              <v-btn
                @click="nextStep"
                width="100%"
                color="orange"
                :disabled="!isStep2Valid"
                style="text-transform: capitalize"
              >
                Next
              </v-btn>
            </v-col>
          </v-row>
        </template>

        <template v-if="step === 3">
          <v-row>
            <v-col cols="12" md="6">
              <v-text-field
                label="Address"
                v-model="form.address"
                required
                variant="underlined"
                prepend-icon="mdi-home"
              ></v-text-field>
            </v-col>
            <v-col cols="12" md="6">
              <v-text-field
                label="Phone Number"
                v-model="form.phone_number"
                required
                variant="underlined"
                prepend-icon="mdi-phone"
              ></v-text-field>
            </v-col>
            <v-col cols="12" md="6">
              <v-text-field
                label="Password"
                v-model="form.password"
                type="password"
                required
                variant="underlined"
                :rules="[v => !!v || 'Password is required']"
                prepend-icon="mdi-lock"
              ></v-text-field>
            </v-col>
            <v-col cols="12" md="6">
              <v-text-field
                label="Confirm Password"
                v-model="form.password_confirmation"
                type="password"
                required
                variant="underlined"
                :rules="[v => !!v || 'Confirm Password is required', v => v === form.password || 'Passwords do not match']"
                prepend-icon="mdi-lock"
              ></v-text-field>
            </v-col>
          </v-row>
          <v-row>
            <v-col>
              <v-btn
                @click="prevStep"
                width="100%"
                color="grey"
                style="text-transform: capitalize"
              >
                Previous
              </v-btn>
            </v-col>
            <v-col>
              <v-btn
                @click="submit"
                width="100%"
                color="green"
                :disabled="!isStep3Valid"
                style="text-transform: capitalize"
              >
                Submit
              </v-btn>
            </v-col>
          </v-row>
        </template>
      </v-card-text>
    </v-card>
  </v-container>
</template>
