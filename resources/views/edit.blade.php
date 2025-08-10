<html>

<head>
    <title></title>
</head>

<body>
    <form action="{{ route('book.update', $buku->id) }}" method="post">
        @csrf
        @method('PUT')
        <table border="1">
            <tr>
                <td>Nama</td>
                <td>:</td>
                <td><input type="text" name="nama" value="{{ $buku->nama }}"></td>
            </tr>
            <tr>
                <td>Pengarang</td>
                <td>:</td>
                <td><input type="text" name="pengarang" value="{{ $buku->pengarang }}"></td>
            </tr>
            <tr>
                <td>Penerbit</td>
                <td>:</td>
                <td><input type="text" name="penerbit" value="{{ $buku->penerbit }}"></td>
            </tr>
            <tr>
                <td colspan="2"></td>
                <td><button type="submit">Save</button></td>
            </tr>
        </table>
    </form>
</body>

</html>
