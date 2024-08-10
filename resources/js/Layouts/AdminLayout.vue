<template>
    <v-app>
      <!-- Navigation Drawer -->
      <v-navigation-drawer
        app
        v-model="drawer"
        fixed
        :style="{ 'min-width': drawerWidth }"
        elevation="0"
        style="background-color:lightblue; color: black;"
      >
        <!-- Logo and Divider -->
        <v-list-item>
          <v-list-item-avatar>
            <v-img src="/Images/360_F_309742761_GPrVMq6nriNIAPgWJebFdg4dvO6MBIhI-removebg-preview.png" alt="site logo" ></v-img>
          </v-list-item-avatar>
        </v-list-item>

        <!-- Navigation Links -->
        <v-list class="mt-10" nav density="compact">
          <v-list-item v-for="(item, i) in items" :key="i">
            <NavLink :href="item.routeName" class="v-list-item" style="color: black;">
              <template v-slot:default="{ href, isActive, isExactActive, isLink }">
                <v-list-item-icon v-if="item.icon" class="list-item-icon">
                  <v-icon>{{ item.icon }}</v-icon>
                </v-list-item-icon>
                <v-list-item-content>
                  <v-list-item-title :class="{ 'primary--text': isExactActive }" v-text="item.text"></v-list-item-title>
                </v-list-item-content>
              </template>
            </NavLink>
          </v-list-item>

          <!-- Feedback Categories Accordion -->

          <!-- Additional Sections -->
          <v-list-group
            v-model="activeSettingsGroup"
            prepend-icon="mdi-cog"
            class="mt-2"
          >
            <template v-slot:activator="{ props }">
              <v-list-item v-bind="props" title="Settings" prepend-icon="mdi-cog" color="white" active-class="active-button"></v-list-item>
            </template>

            <v-list-item :href="route('adminsettings')" prepend-icon="mdi-account-settings" title="Account Settings" color="white" elevation="0" active-class="active-button"></v-list-item>
            <v-list-item :href="route('adminnotification')" prepend-icon="mdi-bell" title="Notifications Settings" color="white" elevation="0" active-class="active-button"></v-list-item>
            <v-list-item :href="route('profile')" prepend-icon="mdi-account" title="My profile" color="white" elevation="0" active-class="active-button"></v-list-item>

          </v-list-group>
          <v-btn
    class="mr-4"
    elevation="2"
    color="red"
    width="100%"
    @click="$inertia.post(route('logout'))"
    style="color: white; background-color: red;text-transform: capitalize;margin-top: 100px">
    <v-icon left>mdi-logout</v-icon> Log Out
</v-btn>


        </v-list>
      </v-navigation-drawer>

      <!-- App Bar -->
      <v-app-bar app class="border-b" height="90" elevation="2">
        <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>


        <v-spacer></v-spacer>

        <!-- Search Bar -->


        <v-chip label elevation="0" class="mr-4">
          <v-switch
            v-model="isDarkMode"
            :label="isDarkMode ? 'Dark Mode' : 'Light Mode'"
            @change="toggleTheme"
            class="mt-6"
          ></v-switch>
        </v-chip>



        <v-chip label elevation="5" style="background-color: orange;" class="mr-4">
          <v-icon>mdi-bell-outline</v-icon> Notifications
        </v-chip>

        <v-chip label class="mr-4" elevation="5" style="background-color: red; color: red;" color="red">
          <ResponsiveNavLink :href="route('logout')" method="post" as="button" style="color: white; background-color: red;">
            <v-icon>mdi-logout</v-icon> Log Out
          </ResponsiveNavLink>
        </v-chip>
      </v-app-bar>

      <!-- Main Content Area -->
      <v-main>
        <v-container fluid class="mt-10">
          <slot></slot>
        </v-container>
      </v-main>
    </v-app>
  </template>

  <script setup>
  import { ref } from 'vue';
  import NavLink from '@/Components/NavLink.vue';
  import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
  import { useTheme } from 'vuetify';
  import { usePage } from '@inertiajs/inertia-vue3';

  const { props } = usePage();

  const theme = useTheme();

  function toggleTheme() {
    theme.global.name.value = theme.global.current.value.dark ? 'light' : 'dark';
  }

  const drawer = ref(true);
  const drawerWidth = '240px'; // Adjust as needed
  const search = ref('');
  const isDarkMode = ref(false);
  const activeGroup = ref(true); // Default to expanded
  const activeSettingsGroup = ref(false); // Default to collapsed
  const activeAdminGroup = ref(false); // Default to collapsed

  function navigateTo(url) {
    Inertia.visit(url);
  }

  function navigateToCategory(url) {
    Inertia.visit(url);
  }

  const items = [
    { text: 'Dashboard', routeName: 'admin', icon: 'mdi-view-dashboard' },
    { text: 'User Management', routeName: 'users', icon: 'mdi-account-multiple' },
    { text: 'Role Management', routeName: 'manage-roles', icon: 'mdi-account-group' },
    { text: 'All Courses', routeName: 'courses', icon: 'mdi-file' },
    { text: 'All Stations', routeName: 'stations', icon: 'mdi-map-marker' },
    { text: 'Manage Students', routeName: 'students', icon: 'mdi-account-group' },
    { text: 'Manage Payments', routeName: 'payments', icon: 'mdi-cash' },
    { text: 'Manage Vehicle', routeName: 'vehicles', icon: 'mdi-car' },
    { text: 'Manage Instructors', routeName: 'instructors', icon: 'mdi-school' },
    { text: 'Manage Lessons', routeName: 'lessons', icon: 'mdi-book-open' },
    { text: 'Reports', routeName: 'reports', icon: 'mdi-file-document' },
  ];

  const categories = props.categories || []; // Ensure categories is an array

  console.log('Categories:', categories);
  </script>

  <style scoped>
  .v-main {
    overflow-y: auto;
    height: calc(100vh - 90px); /* Subtract the height of the app bar */
  }

  .list-item-icon {
    margin-right: 16px; /* Adjust the space between the icon and the text */
  }
  </style>
