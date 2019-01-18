<template>
    <button :class="classes" @click="toggle">
        <i class="fa fa-heart"></i>
        <span v-text="count"></span>
    </button>
</template>

<script>
    export default
    {
        props: ['reply'],
        data()
        {
            return {
                count: this.reply.favoritesCount,
                active: this.reply.isFavorited
            }
        },
        computed:
        {
            classes()
            {
                return [ 'btn', this.active ? 'btn-danger' : 'btn-outline-danger text-danger' ];
            }
        },
        methods:
        {
            toggle()
            {
                axios.post( '/replies/' + this.reply.id + '/favorites' );

                if( this.active )
                {
                    this.destroy();
                }
                else
                {
                    this.create();
                }
            },
            create()
            {
                this.active = true;
                this.count++;
            },
            destroy()
            {
                this.active = false;
                this.count--;
            }
        }
    }
</script>