<template>
  <div style="display:flex; justify-content: center">
    <Form style="display: flex;justify-content: center; flex-direction: column; width: 400px" ref="formInline" :model="formInline" :rules="ruleInline" inline>
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
        <Button type="primary" @click="handleSubmit">Signin</Button>
      </FormItem>
    </Form>
  </div>
</template>

<script>
  export default {
    data () {
      return {
        formInline: {
          email: '',
          password: ''
        },
        ruleInline: {
          email: [
            { required: true, message: 'Please fill in the email.', trigger: 'blur' },
            { type: 'email', trigger: 'blur' }
          ],
          password: [
            { required: true, message: 'Please fill in the password.', trigger: 'blur' },
            { type: 'string', min: 6, message: 'The password length cannot be less than 6 bits', trigger: 'blur' }
          ]
        }
      }
    },
    methods: {
      async handleSubmit() {
        if ((this.formInline.password.trim() == '') && (this.formInline.email.trim() == '')) return this.e('All fields are required')
        if (this.formInline.email.trim() == '') return this.e('email is required')
        if (this.formInline.password.trim() == '') return this.e('password is required')
        const res = await this.callApi('post', 'http://127.0.0.1:8000/api/login',
                this.formInline
        )
        if (res.status === 201) {
          this.s('Auth complete!')
          this.login()
        } else {
          this.swr()
        }

      },
      login () {
        this.$store
                .dispatch('login', {
                  email: this.formInline.email,
                  password: this.formInline.password
                })
                .then(() => {
                  this.$router.push({ name: 'Posts' })
                })
                .catch(err => {
                  console.log(err)
                })
      }
    }
  }
</script>