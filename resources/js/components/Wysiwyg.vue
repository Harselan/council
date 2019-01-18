<template>
	<div>
		<input id="trix" type="hidden" :value="value" :name="name" :placeholder="placeholder">
		<trix-editor ref="trix" input="trix"></trix-editor>
	</div>
</template>

<script>
	import Trix from 'trix';

	export default
	{
	    props: ['name', 'value', 'placeholder', 'shouldClear'],
		mounted()
		{
		    this.$refs.trix.addEventListener( 'trix-change', e =>
		    {
		        this.$emit( 'input', e.target.innerHTML );
		    } );

		    this.$watch( 'shouldClear', () =>
		    {
                this.$refs.trix.value = "";
		    } );
		}
	}
</script>