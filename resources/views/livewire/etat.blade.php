<style>
    .btn-group{
        display: flex;
        flex-direction: row;
        justify-content: space-around;
        flex-wrap: wrap;
    }
    button[type="submit"]{
        padding:10px;
        background-color:blue;
        margin-top:10px;
        border-radius:15px;


    }
</style>
<div>
    {{-- {{$this->form}} --}}

   {{$this->table}}
</div>
