<script setup>
import { reactive, ref } from 'vue';
import Navbar from '@/Components/Navbar.vue'; // Import the Navbar component
import { VCard, VCardTitle, VCardText, VImg, VContainer, VRow, VCol, VCarousel, VCarouselItem, VBtn, VIcon, VFooter, VOverlay } from 'vuetify/components'; // Import Vuetify components



const socialIcons = ref([
  { icon: 'mdi-linkedin', href: 'https://dribbble.com' },
  { icon: 'mdi-facebook', href: 'https://behance.net' },
  { icon: 'mdi-instagram', href: 'https://instagram.com' },
  { icon: 'mdi-twitter', href: 'https://twitter.com' },
]);

const props = defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});

const state = reactive({
    carouselItems: [
        { src: '/Images/smiley-teacher-classroom.jpg' },

    ],
});
</script>

<template>
    <v-app>
        <Navbar /> <!-- Use the Navbar component -->

        <!-- Carousel Section -->
        <v-carousel height="700" class="mt-16" hide-delimiters="true" :show-arrows="false" cycle>
            <v-carousel-item v-for="(item, i) in state.carouselItems" :key="i">
                <v-img :src="item.src" class="carousel-image" cover>
                    <v-overlay class="carousel-overlay" absolute>
                        <div class="overlay-content">
                            <div class="divider top"></div>
                            <div class="divider middle"></div>
                            <div class="divider bottom"></div>
                        </div>
                    </v-overlay>
                    <div class="bottom-overlay">
                        <div class="bottom-content">
                            <h2>Welcome to the Student Application Portal</h2>
                            <p></p>
                            <v-btn color="orange" class="see-courses-btn">SEE AVAILABLE OPPORTUNITIES</v-btn>
                        </div>
                        <div class="bottom-icons">
                            <div>
                                <v-icon>mdi-school</v-icon>
                                <p>Higher Education Tech Leader 2021</p>
                            </div>
                            <div>
                                <v-icon>mdi-star</v-icon>
                                <p>Borcelle Named as World Technology Pioneers in 2021</p>
                            </div>
                            <div>
                                <v-icon>mdi-thumbs-up</v-icon>
                                <p>95% Approval Rating for Applications</p>
                            </div>
                        </div>

                    </div>
                </v-img>
            </v-carousel-item>
        </v-carousel>

        <!-- <v-container >
            <br>
            <v-row>
                <v-col cols="12" md="4">
                    <v-card width="100%" rounded>
                        <v-card-title style="background-color: orange; color: white;">Admission Application Form</v-card-title>
                        <v-card-text>
                            Download the admission application form to apply for new admissions.
                        </v-card-text>
                        <v-card-actions>
                            <v-btn color="green" variant="tonal" width="100%" style="text-transform: capitalize;" href="path/to/admission-application.pdf" download>
                                <v-icon left>mdi-download</v-icon>
                                Download
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-col>
                <v-col cols="12" md="4">
                    <v-card rounded>
                        <v-card-title style="background-color: orange; color: white;">Scholarship Application Form</v-card-title>
                        <v-card-text>
                            Download the scholarship application form to apply for financial aid.
                        </v-card-text>
                        <v-card-actions>
                            <v-btn color="green" variant="tonal" width="100%" style="text-transform: capitalize;" href="path/to/scholarship-application.pdf" download>
                                <v-icon left>mdi-download</v-icon>
                                Download
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-col>
                <v-col cols="12" md="4">
                    <v-card rounded>
                        <v-card-title style="background-color: orange; color: white;">Student Services Guide</v-card-title>
                        <v-card-text>
                            Download our guide to student services to learn more about the support available.
                        </v-card-text>
                        <v-card-actions>
                            <v-btn color="green" variant="tonal" width="100%" style="text-transform: capitalize;" href="path/to/student-services-guide.pdf" download>
                                <v-icon left>mdi-download</v-icon>
                                Download
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-col>
            </v-row>
        </v-container> -->

        <!-- Information Section -->
        <  <v-container class="mt-5">
            <!-- Header Section -->
            <v-row>
                <v-col class="text-center">
                    <h1 class="display-1 font-weight-bold">Study. Work. Achieve.</h1>
                    <p class="subtitle-1">
                        Welcome to Borcelle University's student application portal. We provide a platform for students to apply for both study-based and work-based learning opportunities. Our goal is to offer flexible and comprehensive programs that prepare you for academic success and real-world experiences. Join us and take the first step towards a brighter future.
                    </p>
                </v-col>
            </v-row>

            <!-- Card Section -->
            <v-row class="mt-5" justify="center">
                <v-col cols="12" md="5" class="mb-4">
                    <v-card style="border-radius: 20px 0  20px 0 ;">
                        <v-img
                            src="/Images/african-american-man-front-view.jpg"
                            height="200px"
                            cover
                        ></v-img>
                        <v-card-title class="justify-center">Study-based Learning</v-card-title>
                        <v-card-text class="text-center">Engage in rigorous academic programs designed to deepen your knowledge and expertise in your chosen field.</v-card-text>
                    </v-card>
                </v-col>
                <v-col cols="12" md="5" class="mb-4">
                    <v-card style="border-radius: 0 20px 0  20px ;">
                        <v-img
                            src="/Images/medium-shot-hard-working-student.jpg"
                            height="200px"
                            cover
                        ></v-img>
                        <v-card-title class="justify-center">Work-based Learning</v-card-title>
                        <v-card-text class="text-center">Gain hands-on experience through internships and practical assignments that enhance your career readiness.</v-card-text>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
        <!-- Footer Section -->
        <v-footer
      color="green"

    >
      <v-col
        class="text-center"
        cols="12"
      >
      <v-row justify="center">
          <v-col class="mb-4">
            <v-img
              src="Images/logo.png"
              alt="Satyamstudio Logo"
              max-height="90"
              contain
            ></v-img>
            <span class="white--text font-weight-bold">Borcelle University</span>
          </v-col>
        </v-row>
        <v-row justify="center">
          <v-btn text class="white--text" elevation="0" color="transparent">Home</v-btn>
          <v-btn text class="white--text" elevation="0" color="transparent">About</v-btn>
          <v-btn text class="white--text" elevation="0" color="transparent">Service</v-btn>
          <v-btn text class="white--text" elevation="0" color="transparent">Contact Us</v-btn>
        </v-row>
        <v-row justify="center" class="mt-4">
          <v-btn
            v-for="(icon, index) in socialIcons"
            :key="index"
            :icon="icon.icon"
            :href="icon.href"
            class="mx-2"
            elevation="0" color="transparent"
          ></v-btn>
        </v-row>
        <v-divider class="my-4"></v-divider>
        <div class="white--text">&copy; Copyright Borcelle University</div>
      </v-col>
    </v-footer>
    </v-app>
</template>

<style scoped>

.v-btn.icon {
  border-radius: 50%;
  width: 40px;
  height: 40px;
}
.v-divider {
  background-color: rgba(255, 255, 255, 0.1);
}
.mt-16 {
    margin-top: 4rem;
}
.carousel-image {
    height: 700px; /* Adjust the height as needed */
    object-fit: cover;
}
.carousel-overlay {
    background-color: rgba(0, 0, 0, 0.5); /* Transparent overlay */
}
.overlay-content {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    height: 100%;
    width: 100%;
}
.divider {
    background-color: white;
    height: 2px;
    width: 100%;
    opacity: 0.7;
}
.divider.top {
    margin-top: 20px;
}
.divider.middle {
    flex-grow: 1;
}
.divider.bottom {
    margin-bottom: 20px;
}
.bottom-overlay {
    position: absolute;
    bottom: 0;
    width: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    padding: 20px;
    color: white;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}
.bottom-content {
    margin-bottom: 10px;
}
.bottom-content h2 {
    font-size: 2rem;
    font-weight: bold;
}
.bottom-content p {
    font-size: 1.2rem;
    margin: 10px 0;
}
.see-courses-btn {
    background-color: orange;
    color: white;
    text-transform: uppercase;
    font-weight: bold;
}
.bottom-icons {
    display: flex;
    justify-content: center;
    gap: 20px;
    margin-top: 10px;
}
.bottom-icons div {
    display: flex;
    flex-direction: column;
    align-items: center;
}
.bottom-icons p {
    margin-top: 5px;
    font-size: 0.9rem;
}
.partners {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    margin-top: 20px;
}
.partners p {
    font-size: 0.9rem;
}
.mt-5 {
    margin-top: 3rem;
}

.mb-4 {
    margin-bottom: 1.5rem;
}

.text-center {
    text-align: center;
}

.display-1 {
    font-size: 2.5rem;
    font-weight: bold;
}

.subtitle-1 {
    font-size: 1.25rem;
    color: #555;
}

.v-card-title {
    text-align: center;
    font-size: 1.1rem;
    font-weight: bold;
    padding: 1rem 0;
}
</style>
