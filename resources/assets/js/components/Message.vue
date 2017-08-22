<template>
    <div>
        <div class="box">
          <article class="media">
            <div class="media-content">
              <div class="content">
                <p>
                  <strong>{{ username }}</strong>
                  <br>
                  {{ body }}
                </p>
              </div>
            </div>
          </article>
        </div>
        <br>


    </div>
</template>

<script>
    export default {

        props: ['body', 'username'],

        data() {
            return {

            }
        },

        mounted() {
            // this.listenForNewMessage();
        },


        methods: {

        }
    }
</script>
