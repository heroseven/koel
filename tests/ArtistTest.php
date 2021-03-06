<?php

use App\Models\Artist;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ArtistTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
    }

    public function testShouldBeCreatedWithUniqueNames()
    {
        $name = 'Foo Fighters';
        $artist = Artist::get($name);

        $this->assertEquals($name, $artist->name);

        // Should be only 3 records: UNKNOWN_ARTIST, VARIOUS_ARTISTS, and our Dave Grohl's band
        $this->assertEquals(3, Artist::all()->count());

        Artist::get($name);

        // Should still be 3.
        $this->assertEquals(3, Artist::all()->count());
    }

    public function testArtistWithEmptyNameShouldBeUnknown()
    {
        $this->assertEquals(Artist::UNKNOWN_NAME, Artist::get('')->name);
    }

    public function testUtf16Names()
    {
        $name = file_get_contents(dirname(__FILE__).'/blobs/utf16');

        $artist = Artist::get($name);
        $artist = Artist::get($name); // to make sure there's no constraint exception

        $this->assertEquals($artist->id, Artist::get($name)->id);
    }
}
