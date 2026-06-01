<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head, usePage } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: Boolean,
    status: String,
});

const user = usePage().props.auth.user;

const initials = user.name
    .split(' ')
    .map((word) => word[0])
    .join('')
    .slice(0, 2)
    .toUpperCase();
</script>

<template>
   <Head title="Profile" />

    <AuthenticatedLayout>
        <template #header>
            <div>
                <h2 class="text-2xl font-bold text-white">
                    Profile Settings
                </h2>
                <p class="mt-2 text-sm text-slate-300">
                    Manage your account information and security settings.
                </p>
            </div>
        </template>

        <div class="min-h-screen bg-[#07111f] px-4 py-8 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-6xl space-y-5">
                <div class="overflow-hidden rounded-3xl border border-white/10 bg-[#0f172a]/95 shadow-2xl">
                    <div class="bg-gradient-to-r from-indigo-500 via-violet-600 to-purple-700 px-8 py-10">
                        <div class="flex items-center gap-5">
                            <div class="flex h-24 w-24 items-center justify-center overflow-hidden rounded-full bg-white text-3xl font-bold text-indigo-600 shadow-xl">
                       <img
                        v-if="user.avatar"
                       :src="`/storage/${user.avatar}`"
                       :alt="user.name"
                       class="h-full w-full object-cover"
                         />

                       <span v-else>
                         {{ initials }}
                     </span>
                       </div>

                            <div>
                                <h3 class="text-3xl font-bold text-white">
                                    {{ user.name }}
                                </h3>
                                <p class="mt-1 text-base text-indigo-100">
                                    {{ user.email }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-[#101b2d] px-8 py-8">
                        <UpdateProfileInformationForm
                            :must-verify-email="mustVerifyEmail"
                            :status="status"
                            class="max-w-full"
                        />
                    </div>
                </div>

                <div class="rounded-3xl border border-white/10 bg-[#0f172a]/95 p-6 shadow-xl">
                    <UpdatePasswordForm class="max-w-full" />
                </div>

                <div class="rounded-3xl border border-red-500/20 bg-[#0f172a]/95 p-6 shadow-xl">
                    <DeleteUserForm class="max-w-full" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>