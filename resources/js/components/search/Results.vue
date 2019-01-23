<template>
	<table style="border-collapse: collapse">
		<tbody>
			<result v-if="results.length" v-for="result in results" :data="result"></result>
			<tr v-else>
				<td>Nothing here.</td>
			</tr>
		</tbody>
	</table>
</template>

<script >
	import Result from './Result.vue';

    export default
    {
        props: [ 'results' ],
	    components: { Result }
    }
</script >