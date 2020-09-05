<template>
    <div>
        <crud-layout
            :columns="columns"
            :modelName="modelName"
            :formRecord="form"
            :formComponent="formComponent"
        >
            <div class="col-lg-4" slot="stats">
                <stats-card title="Admin"
                    type="gradient-primary"
                    :sub-title="admin.length.toString()"
                    icon="ni ni-money-coins"
                    class="mb-4 mb-xl-0"
                >
                </stats-card>
            </div>

            <div class="col-lg-4" slot="stats">
                <stats-card title="Pengelola"
                    type="gradient-success"
                    :sub-title="pengelola.length.toString()"
                    icon="ni ni-money-coins"
                    class="mb-4 mb-xl-0"
                >
                </stats-card>
            </div>

            <div class="col-lg-4" slot="stats">
                <stats-card title="Pegawai"
                    type="gradient-warning"
                    :sub-title="pegawai.length.toString()"
                    icon="ni ni-money-coins"
                    class="mb-4 mb-xl-0"
                >
                </stats-card>
            </div>

            <template slot="table-rows">
                <tr v-for="(user, index) in users" :key="user.id">
                    <td>{{ index + 1 }}</td>
                    <td class="text-capitalize">{{ user.name }}</td>
                    <td>{{ user.email }}</td>
                    <td>{{ user.role }}</td>
                    <td>{{ user.address }}</td>
                    <td>{{ user.phone }}</td>
                    <td>{{ user.created_at }}</td>
                    <td>
                        <action-button 
                            :formRecord="form"
                            :record="user"
                            :modelName="modelName"
                        />
                    </td>
                </tr>
            </template>
        </crud-layout>
    </div>
</template>

<script>
import UserForm from '../forms/UserForm'
import { Form } from 'vform'
import { mapGetters } from 'vuex'

export default {
    data: () => ({
        columns: [
            '#', 'Nama', 'Email', 'Hak Akses', 'Alamat', 'No Hp', 'Terakhir Diedit'
        ],
        form: new Form({
            id: '',
            name: '',
            email: '',
            role: '',
            password: '',
            phone: '',
            address: '',
        }),
        modelName: 'users',
        formComponent: UserForm,
    }),

    computed: {
        ...mapGetters({
            users: 'users',
            admin: 'adminUsers',
            pengelola: 'pengelolaUsers',
            pegawai: 'pegawaiUsers'
        })
    }
}
</script>