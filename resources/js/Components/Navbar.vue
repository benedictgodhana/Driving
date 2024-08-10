<template>
    <v-app-bar
      :class="['app-bar', { 'bg-orange': !isScrolled, 'bg-green': isScrolled }]"
      app
      elevation="0"
      dense
    >
      <v-container>
        <v-row align="center" justify="space-between">
          <!-- Logo -->
          <v-img
            src="/Images/logo.png"
            contain
            max-width="130"
            class="logo"
          ></v-img>

          <v-col class="d-flex justify-end">
            <template v-if="canLogin">
              <v-btn
                v-if="auth && auth.user"
                :href="route('dashboard')"
                class="btn ms-4"
                style="text-transform: capitalize;"
              >
                Dashboard
              </v-btn>
              <template v-else>
                <v-btn
                  v-if="canRegister"
                  :href="route('register')"
                  class="btn ms-4"
                  color="white"
                  style="text-transform: capitalize; color:black;font-weight: 900; "
                >
                  <v-icon size="19">mdi-account-plus</v-icon> Register
                </v-btn>
                <v-btn
                  :href="route('login')"
                  class="btn ms-4"
                  color="white"
                  style="text-transform: capitalize;font-weight: 900;"
                >
                  <v-icon size="19">mdi-login</v-icon> Log in
                </v-btn>
              </template>
            </template>
          </v-col>
        </v-row>
      </v-container>
    </v-app-bar>
  </template>

  <script setup>
  import { ref, onMounted, onUnmounted } from 'vue';
  import { usePage } from '@inertiajs/vue3';

  const { props } = usePage();
  const { canLogin, canRegister, auth } = props; // Ensure 'auth' is destructured from props

  const isScrolled = ref(false);

  const handleScroll = () => {
    isScrolled.value = window.scrollY > 0;
  };

  onMounted(() => {
    window.addEventListener('scroll', handleScroll);
  });

  onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
  });
  </script>

  <style scoped>
  .app-bar {
    transition: background-color 0.3s ease;
  }

  .bg-transparent {
    background-color: transparent !important;
  }

  .bg-green {
    background-color: green !important;
  }

  .btn {
    padding: 0.5rem 1rem;
    border: 1px solid transparent;
    border-radius: 0.375rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    transition: background-color 0.3s ease, color 0.3s ease;
  }

  .ms-4 {
    margin-left: 1rem;
  }

  .logo {
    max-width: 150px;
    height: auto;
  }
  </style>
