<html>

<head>
    <title></title>
</head>

<body>
    <form action="{{ route('book.store') }}" method="post">
        @csrf
        @method('POST')
        <table border="1">
            <tr>
                <td>Nama</td>
                <td>:</td>
                <td><input type="text" name="nama"></td>
            </tr>
            <tr>
                <td>Pengarang</td>
                <td>:</td>
                <td><input type="text" name="pengarang"></td>
            </tr>
            <tr>
                <td>Penerbit</td>
                <td>:</td>
                <td><input type="text" name="penerbit"></td>
            </tr>
            <tr>
                <td colspan="2"></td>
                <td><button type="submit">Save</button></td>
            </tr>
        </table>
    </form>
</body>

</html>
