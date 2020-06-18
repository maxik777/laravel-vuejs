<template>
    <div style="display:flex; justify-content: center">
        <Form style="display: flex;justify-content: center; flex-direction: column; width: 400px" ref="formInline" :model="formInline" :rules="ruleInline" inline>
            <FormItem prop="user">
                <Input type="text" v-model="formInline.name" placeholder="Username">
                    <Icon type="ios-person-outline" slot="prepend"></Icon>
                </Input>
            </FormItem>
            <FormItem prop="email">
                <Input type="text" v-model="formInline.email" placeholder="E-mail">
                    <Icon type="ios-at-outline" slot="prepend"> slot="prepend"></Icon>
                </Input>
            </FormItem>
            <FormItem prop="password">
                <Input type="password" v-model="formInline.password" placeholder="Password">
                    <Icon type="ios-lock-outline" slot="prepend"></Icon>
                </Input>
            </FormItem>
            <FormItem>
                <Button type="primary" @click="handleSubmit('formInline')">Register</Button>
            </FormItem>
        </Form>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                formInline: {
                    name: '',
                    email: '',
                    password: ''
                },
                ruleInline: {
                    name: [
                        { required: true, message: 'Please fill in the user name', trigger: 'blur'},
                        { type: 'string', min: 2, message: 'The user name length cannot be less than 2 bits', trigger: 'blur'}
                    ],
                    password: [
                        { required: true, message: 'Please fill in the password.', trigger: 'blur' },
                        { type: 'string', min: 6, message: 'The password length cannot be less than 6 bits', trigger: 'blur' }
                    ],
                    email: [
                        { required: true, message: 'Please fill in the email.', trigger: 'blur' },
                        { type: 'email', trigger: 'blur' }
                    ]
                }
            }
        },
        methods: {
            async handleSubmit() {
                if ((this.formInline.name.trim() == '') && (this.formInline.password.trim() == '') && (this.formInline.email.trim() == '')) return this.e('All fields are required')
                if (this.formInline.name.trim() == '') return this.e('name is required')
                if (this.formInline.email.trim() == '') return this.e('email is required')
                if (this.formInline.password.trim() == '') return this.e('password is required')
                const res = await this.callApi('post', 'http://127.0.0.1:8000/api/register',
                    this.formInline
                )
                if (res.status === 200) {
                    this.s('Registration was success, please, sign in to manage data!')
                    this.$router.push({ path: '/login' })
                } else {
                    this.swr()
                }

            },
        }
    }
</script>