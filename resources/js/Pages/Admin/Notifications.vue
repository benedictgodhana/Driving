<template>
    <Head title="Dashboard" />
    <AdminLayout>
        <v-container width="100%" style="max-width: 1600px">
            <v-row>
                <!-- Profile Card Section -->

                <!-- Notification List Section -->
                <v-col cols="12" md="4" class="notification-list">
                    <v-card rounded style="margin-top: -10px">
                        <v-card-title
                            style="background-color: green; color: white"
                            class="text-center"
                        >
                            Notifications
                        </v-card-title>
                        <v-card-text>
                            <!-- Search and Filter -->
                            <v-row>
                                <v-col cols="12" md="6">
                                    <br />
                                    <v-text-field
                                        v-model="search"
                                        prepend-inner-icon="mdi-magnify"
                                        label="Search"
                                        single-line
                                        variant="outlined"
                                        hide-details
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12" md="6">
                                    <br />
                                    <v-select
                                        v-model="selectedFilter"
                                        :items="filters"
                                        label="Filter by"
                                        variant="outlined"
                                    ></v-select>
                                </v-col>
                            </v-row>
                            <!-- Notification List -->
                            <v-list>
                                <v-list-item
                                    v-for="(
                                        notification, index
                                    ) in paginatedNotifications"
                                    :key="index"
                                    @click="selectNotification(notification)"
                                    :class="{
                                        'active-item':
                                            selectedNotification ===
                                            notification,
                                        'bold-item': isRecent(notification),
                                    }"
                                >
                                    <v-list-item-action> </v-list-item-action>
                                    <v-list-item-content>
                                        <v-row>
                                            <v-col>
                                                <v-list-item-title>
                                                    {{
                                                        notification.feedback
                                                            .subject
                                                    }}
                                                </v-list-item-title>
                                                <v-list-item-subtitle>
                                                    {{
                                                        notification.feedback
                                                            .feedback
                                                    }}
                                                </v-list-item-subtitle>
                                                <v-list-item-subtitle>
                                                    {{
                                                        formatDateTime(
                                                            notification.created_at
                                                        )
                                                    }}
                                                </v-list-item-subtitle>
                                            </v-col>
                                        </v-row>
                                    </v-list-item-content>
                                </v-list-item>
                            </v-list>
                            <!-- Pagination Controls -->
                            <v-pagination
                                v-model="currentPage"
                                :length="totalPages"
                                class="mt-4"
                                :total-visible="1"
                                prev-icon="mdi-chevron-left"
                                next-icon="mdi-chevron-right"
                                rounded
                            ></v-pagination>
                        </v-card-text>
                    </v-card>
                </v-col>

                <!-- Notification Details Section -->
                <v-col cols="12" md="4" style="margin-top: -10px">
                    <v-card v-if="selectedNotification">
                        <v-card-title
                            style="background-color: green; color: white"
                        >
                            <v-btn
                                icon
                                @click="selectedNotification = null"
                                style="color: black"
                            >
                                <v-icon color="orange">mdi-arrow-left</v-icon>
                            </v-btn>
                            <span class="ml-2">Notification Details</span>
                        </v-card-title>
                        <v-card-text>
                            <br />
                            <p>
                                <strong>Date and Time:</strong>
                                {{
                                    formatDateTime(
                                        selectedNotification.created_at
                                    )
                                }}
                            </p>
                            <br />
                            <hr />
                            <p class="mt-3">
                                <strong>Subject:</strong>
                                {{ selectedNotification.feedback.subject }}
                            </p>
                            <br />
                            <hr />
                            <p class="mt-3 text-center">
                                <strong>Content:</strong>
                            </p>
                            <br />
                            <div v-if="selectedNotification.feedback">
                                <v-row>
                                    <v-col>
                                        <p class="mt-3">
                                            <strong>Feedback Subject:</strong>
                                            {{
                                                selectedNotification.feedback
                                                    .subject
                                            }}
                                        </p>
                                    </v-col>

                                    <v-col>
                                        <p class="mt-3">
                                            <strong class="mr-4">Name:</strong>
                                            {{
                                                selectedNotification.feedback
                                                    .name || "N/A"
                                            }}
                                        </p>
                                    </v-col>

                                    <v-col>
                                        <p class="mt-3 ">
                                            <strong class="mr-4">Email:</strong>
                                            {{
                                                selectedNotification.feedback
                                                    .email || "N/A"
                                            }}
                                        </p>
                                    </v-col>
                                </v-row>
                                <v-row>
                                    <v-col>
                                        <p class="mt-3">
                                            <strong>Category:</strong>
                                            {{
                                                selectedNotification.feedback
                                                    .category.name || 'N/A'
                                            }}
                                        </p>
                                    </v-col>

                                    <v-col>
                                        <p class="mt-3">
                                            <strong>Subcategory:</strong>
                                            {{
                                                selectedNotification.feedback
                                                    .subcategory.name || 'N/A'
                                            }}
                                        </p>
                                    </v-col>

                                    <v-col>
                                        <p class="mt-3">
                                            <strong>Feedback:</strong>
                                            {{
                                                selectedNotification.feedback
                                                    .feedback || 'N/A'
                                            }}
                                        </p>
                                    </v-col>
                                </v-row>
                                <br />
                                <v-select
                                    v-model="selectedNotification.feedback.status"
                                    :items="statusOptions"
                                    label="Feedback Status"
                                    variant="underlined"
                                    dense
                                ></v-select>
                                <v-btn
                                    width="100%"
                                    color="orange"
                                    style="text-transform: capitalize"
                                >
                                    Change Status
                                </v-btn>
                            </div>
                        </v-card-text>
                    </v-card>
                    <v-card v-else>
                        <v-card-title
                            style="
                                background-color: green;
                                color: white;
                                text-align: center;
                            "
                        >
                            Select a Notification
                        </v-card-title>
                        <v-card-text class="text-center">
                            <p>
                                Please select a notification from the list to
                                view details.
                            </p>
                        </v-card-text>
                    </v-card>
                </v-col>

                <!-- User List Section -->
                <v-col cols="12" md="4" class="user-list">
                    <v-card rounded style="margin-top: -10px">
                        <v-card-title
                            style="background-color: green; color: white"
                            class="text-center"
                        >
                            Users
                        </v-card-title>
                        <v-card-text>
                            <!-- User List -->
                            <v-list>
                                <v-list-item
                                    v-for="(user, index) in users"
                                    :key="index"
                                    @click="selectUser(user)"
                                    :class="{
                                        'active-item': selectedUser === user,
                                    }"
                                >
                                    <v-list-item-content>
                                        <v-row>
                                            <v-col>
                                                <v-list-item-title>
                                                    {{ user.name }}
                                                </v-list-item-title>
                                                <v-list-item-subtitle>
                                                    {{ user.email }}
                                                </v-list-item-subtitle>
                                            </v-col>
                                        </v-row>
                                    </v-list-item-content>
                                </v-list-item>
                            </v-list>
                        </v-card-text>
                    </v-card>
                    <v-card v-if="selectedUser">
                        <v-card-title
                            style="background-color: green; color: white"
                        >
                            <v-btn
                                icon
                                @click="selectedUser = null"
                                style="color: black"
                            >
                                <v-icon color="orange">mdi-arrow-left</v-icon>
                            </v-btn>
                            <span class="ml-2">Chat with {{ selectedUser.name }}</span>
                        </v-card-title>
                        <v-card-text>
                            <!-- Chat Messages -->
                            <v-list class="chat-messages">
                                <v-list-item
                                    v-for="(message, index) in messages"
                                    :key="index"
                                >
                                    <v-list-item-content>
                                        <v-list-item-title>
                                            {{ message.sender }}:
                                        </v-list-item-title>
                                        <v-list-item-subtitle>
                                            {{ message.text }}
                                        </v-list-item-subtitle>
                                    </v-list-item-content>
                                </v-list-item>
                            </v-list>
                            <!-- Send Message -->
                            <v-row>
                                <v-col cols="9">
                                    <v-text-field
                                        v-model="newMessage"
                                        label="Type a message"
                                        variant="outlined"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="3">
                                    <v-btn @click="sendMessage" color="orange">
                                        Send
                                    </v-btn>
                                </v-col>
                            </v-row>
                        </v-card-text>
                    </v-card>
                    <v-card v-else>
                        <v-card-title
                            style="
                                background-color: green;
                                color: white;
                                text-align: center;
                            "
                        >
                            Select a User to Chat
                        </v-card-title>
                        <v-card-text class="text-center">
                            <p>
                                Please select a user from the list to start a
                                chat.
                            </p>
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';


const search = ref('');
const selectedFilter = ref(null);
const filters = ref(['Recent', 'Oldest']);
const selectedNotification = ref(null);
const selectedUser = ref(null);
const notifications = ref([
    // Array of notifications
]);
const users = ref([
    { name: 'User 1', email: 'user1@example.com' },
    { name: 'User 2', email: 'user2@example.com' },
    // Add more users as needed
]);
const messages = ref([]);
const newMessage = ref('');

const statusOptions = [
    { text: 'Resolved', value: 'Resolved' },
    { text: 'Pending', value: 'Pending' },
    { text: 'Closed', value: 'Closed' },
];

const currentPage = ref(1);
const itemsPerPage = ref(5);
const totalPages = computed(() =>
    Math.ceil(filteredNotifications.value.length / itemsPerPage.value)
);

const filteredNotifications = computed(() =>
    notifications.value.filter((notification) =>
        notification.feedback.subject
            .toLowerCase()
            .includes(search.value.toLowerCase())
    )
);

const paginatedNotifications = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage.value;
    const end = start + itemsPerPage.value;
    return filteredNotifications.value.slice(start, end);
});

const isRecent = (notification) => {
    const oneWeekAgo = new Date();
    oneWeekAgo.setDate(oneWeekAgo.getDate() - 7);
    return new Date(notification.created_at) > oneWeekAgo;
};

const selectNotification = (notification) => {
    selectedNotification.value = notification;
};

const formatDateTime = (dateTime) => {
    const options = {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: 'numeric',
        minute: 'numeric',
    };
    return new Date(dateTime).toLocaleString(undefined, options);
};

const selectUser = (user) => {
    selectedUser.value = user;
    messages.value = [
        { sender: 'Me', text: 'Hello!' },
        { sender: selectedUser.value.name, text: 'Hi there!' },
    ];
};

const sendMessage = () => {
    if (newMessage.value.trim()) {
        messages.value.push({ sender: 'Me', text: newMessage.value });
        newMessage.value = '';
    }
};
</script>

<style scoped>
.bold-item {
    font-weight: bold;
}
.active-item {
    background-color: lightgray;
}
.notification-list {
    max-height: 600px;
    overflow-y: auto;
}
.user-list {
    max-height: 600px;
    overflow-y: auto;
}
.chat-messages {
    max-height: 300px;
    overflow-y: auto;
    margin-bottom: 20px;
}
</style>
