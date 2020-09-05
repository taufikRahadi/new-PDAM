<template>
    <div>
        <base-input alternative=""
            label="Nama Lengkap"
            placeholder="Nama Lengkap"
            input-classes="form-control-alternative"
            v-model="formRecord.name"
        />

        <base-input
            alternative=""
            label="No Hp"
            placeholder="No Hp"
            input-classes="form-control-alternative"
            v-model="formRecord.phone"
        />

        <base-input alternative="" label="User">
            <select class="form-control form-control-alternative" v-model="formRecord.user_id">
                <option value="">Pilih User</option>
                <option v-for="user in users" :key="user.id" :value="user.id">
                    {{ user.name }} &ndash; {{ user.email }}
                </option>
            </select>
        </base-input>

        <base-input 
            alternative=""
            label="Alamat"
            input-classes="form-control-alternative"
            placeholder="Alamat"
            v-model="formRecord.address"
        />
    </div>
</template>

<script>
export default {
    props: ['formRecord'],

    computed: {
        users() {
            return this.$store.getters.users
        }
    },

    methods: {
        fetchUser() {
            this.$store.dispatch('fetchData', 'users')
        }
    },

    mounted() {
        this.fetchUser()
    }
}
</script>