<table class="map-table" id="usaha-table">
    <thead>
        <tr>
            <th>Nama Usaha</th>
            <th>Kategori</th>
            <th>Alamat</th>
            <th>Nomor Telepon</th>
            <th>Website</th>
        </tr>
    </thead>
    <tbody>
        @foreach($allUsahaGmaps as $usaha)
            <tr>
                <td class="map-table__name">{{ $usaha->nama_usaha }}</td>
                <td><span class="map-table__badge">{{ $usaha->kategori ?? '-' }}</span></td>
                <td class="map-table__address">{{ $usaha->alamat ?? '-' }}</td>
                <td>{{ $usaha->nomor_telepon ?? '-' }}</td>
                <td>
                    @if($usaha->website)
                        <a href="{{ $usaha->website }}" target="_blank" class="map-table__link">Buka</a>
                    @else
                        -
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>