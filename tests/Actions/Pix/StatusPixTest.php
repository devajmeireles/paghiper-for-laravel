<?php

use DevAjMeireles\PagHiper\Actions\Pix\StatusPix;
use DevAjMeireles\PagHiper\Enums\Cast;
use DevAjMeireles\PagHiper\Exceptions\PagHiperRejectException;
use DevAjMeireles\PagHiper\PagHiper;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;

it('should be able to consult pix status casting to array', function () {
    $result = [
        "result"           => "success",
        "response_message" => "transacao encontrada",
        "status"           => "pending",
        "status_date"      => "2017-07-14 21:21:02",
        "due_date"         => "2017-07-12",
        "due_datetime"     => "2017-07-12 23:59:59",
        "value_cents"      => "2000",
        "pix_code"         => [
            "qrcode_base64"    => "iVBORw0KGgoAAAANSUhEUgAAAPoAAAD6A",
            "qrcode_image_url" => "https://pix.paghiper.com/pixcode/?a5eef1cc3013a9a0063ff657229efde603122f9898b26f7c9e5c7d2cc950fdde990aad6d0693bbc3d61b28a640cc775db1eddc55261462ba514f6a41b7d3268c/39JI4HD3FF7NC27Y/84700950.png",
            "emv"              => "00020101021226770014BR.GOV.BCB.PIX2555api.itau/pix/qr/v1/ce7f9743-6575-485a-86da-a0f56cf136565204000053039865802BR5925PAGHIPER SERV ONLINE EIRE6009SAO PAULO62070503***630409AD",
            "pix_url"          => "https://pix.paghiper.com/qrcode/180068c7/HF97T5SH2ZQNLF6Z/30039",
            "bacen_url"        => "https://pix.bcb.gov.br/qr/MDAwMjAxMDEwMjEyMjY3NzAwMTRCUi5HT1YuQkNCLlBJWDI1NTVhcGkuaXRhdS9waXgvcXIvdjEvY2U3Zjk3NDMtNjU3NS00ODVhLTg2ZGEtYTBmNTZjZjEzNjU2NTIwNDAwMDA1MzAzOTg2NTgwMkJSNTkyNVBBR0hJUEVSIFNFUlYgT05MSU5FIEVJUkU2MDA5U0FPIFBBVUxPNjIwNzA1MDMqKio2MzA0MDlBRA==",
        ],
        "http_code" => "201",
    ];

    fakePixResponse(StatusPix::END_POINT, 'status_request', $result);

    $status = (new PagHiper())->pix()->status('BPV661O7AVLORCN5');

    expect($status)
        ->toBeArray()
        ->and($status)
        ->toBe($result);
});

it('should be able to consult pix status casting to json', function () {
    $result = [
        "result"           => "success",
        "response_message" => "transacao encontrada",
        "status"           => "pending",
        "status_date"      => "2017-07-14 21:21:02",
        "due_date"         => "2017-07-12",
        "due_datetime"     => "2017-07-12 23:59:59",
        "value_cents"      => "2000",
        "pix_code"         => [
            "qrcode_base64"    => "iVBORw0KGgoAAAANSUhEUgAAAPoAAAD6A",
            "qrcode_image_url" => "https://pix.paghiper.com/pixcode/?a5eef1cc3013a9a0063ff657229efde603122f9898b26f7c9e5c7d2cc950fdde990aad6d0693bbc3d61b28a640cc775db1eddc55261462ba514f6a41b7d3268c/39JI4HD3FF7NC27Y/84700950.png",
            "emv"              => "00020101021226770014BR.GOV.BCB.PIX2555api.itau/pix/qr/v1/ce7f9743-6575-485a-86da-a0f56cf136565204000053039865802BR5925PAGHIPER SERV ONLINE EIRE6009SAO PAULO62070503***630409AD",
            "pix_url"          => "https://pix.paghiper.com/qrcode/180068c7/HF97T5SH2ZQNLF6Z/30039",
            "bacen_url"        => "https://pix.bcb.gov.br/qr/MDAwMjAxMDEwMjEyMjY3NzAwMTRCUi5HT1YuQkNCLlBJWDI1NTVhcGkuaXRhdS9waXgvcXIvdjEvY2U3Zjk3NDMtNjU3NS00ODVhLTg2ZGEtYTBmNTZjZjEzNjU2NTIwNDAwMDA1MzAzOTg2NTgwMkJSNTkyNVBBR0hJUEVSIFNFUlYgT05MSU5FIEVJUkU2MDA5U0FPIFBBVUxPNjIwNzA1MDMqKio2MzA0MDlBRA==",
        ],
        "http_code" => "201",
    ];

    fakePixResponse(StatusPix::END_POINT, 'status_request', $result);

    $status = (new PagHiper())->pix(Cast::Json)->status('BPV661O7AVLORCN5');

    expect($status)
        ->toBeJson()
        ->and($status)
        ->toBe(collect($result)->toJson());
});

it('should be able to consult pix status casting to collection', function (Cast $cast) {
    $result = [
        "result"           => "success",
        "response_message" => "transacao encontrada",
        "status"           => "pending",
        "status_date"      => "2017-07-14 21:21:02",
        "due_date"         => "2017-07-12",
        "due_datetime"     => "2017-07-12 23:59:59",
        "value_cents"      => "2000",
        "pix_code"         => [
            "qrcode_base64"    => "iVBORw0KGgoAAAANSUhEUgAAAPoAAAD6A",
            "qrcode_image_url" => "https://pix.paghiper.com/pixcode/?a5eef1cc3013a9a0063ff657229efde603122f9898b26f7c9e5c7d2cc950fdde990aad6d0693bbc3d61b28a640cc775db1eddc55261462ba514f6a41b7d3268c/39JI4HD3FF7NC27Y/84700950.png",
            "emv"              => "00020101021226770014BR.GOV.BCB.PIX2555api.itau/pix/qr/v1/ce7f9743-6575-485a-86da-a0f56cf136565204000053039865802BR5925PAGHIPER SERV ONLINE EIRE6009SAO PAULO62070503***630409AD",
            "pix_url"          => "https://pix.paghiper.com/qrcode/180068c7/HF97T5SH2ZQNLF6Z/30039",
            "bacen_url"        => "https://pix.bcb.gov.br/qr/MDAwMjAxMDEwMjEyMjY3NzAwMTRCUi5HT1YuQkNCLlBJWDI1NTVhcGkuaXRhdS9waXgvcXIvdjEvY2U3Zjk3NDMtNjU3NS00ODVhLTg2ZGEtYTBmNTZjZjEzNjU2NTIwNDAwMDA1MzAzOTg2NTgwMkJSNTkyNVBBR0hJUEVSIFNFUlYgT05MSU5FIEVJUkU2MDA5U0FPIFBBVUxPNjIwNzA1MDMqKio2MzA0MDlBRA==",
        ],
        "http_code" => "201",
    ];

    fakePixResponse(StatusPix::END_POINT, 'status_request', $result);

    $status = (new PagHiper())->pix($cast)->status('BPV661O7AVLORCN5');

    expect($status)->toBeInstanceOf(Collection::class);
})->with([
    Cast::Collect,
    Cast::Collection,
]);

it('should be able to consult pix status casting to original response', function () {
    $result = [
        "result"           => "success",
        "response_message" => "transacao encontrada",
        "status"           => "pending",
        "status_date"      => "2017-07-14 21:21:02",
        "due_date"         => "2017-07-12",
        "due_datetime"     => "2017-07-12 23:59:59",
        "value_cents"      => "2000",
        "pix_code"         => [
            "qrcode_base64"    => "iVBORw0KGgoAAAANSUhEUgAAAPoAAAD6A",
            "qrcode_image_url" => "https://pix.paghiper.com/pixcode/?a5eef1cc3013a9a0063ff657229efde603122f9898b26f7c9e5c7d2cc950fdde990aad6d0693bbc3d61b28a640cc775db1eddc55261462ba514f6a41b7d3268c/39JI4HD3FF7NC27Y/84700950.png",
            "emv"              => "00020101021226770014BR.GOV.BCB.PIX2555api.itau/pix/qr/v1/ce7f9743-6575-485a-86da-a0f56cf136565204000053039865802BR5925PAGHIPER SERV ONLINE EIRE6009SAO PAULO62070503***630409AD",
            "pix_url"          => "https://pix.paghiper.com/qrcode/180068c7/HF97T5SH2ZQNLF6Z/30039",
            "bacen_url"        => "https://pix.bcb.gov.br/qr/MDAwMjAxMDEwMjEyMjY3NzAwMTRCUi5HT1YuQkNCLlBJWDI1NTVhcGkuaXRhdS9waXgvcXIvdjEvY2U3Zjk3NDMtNjU3NS00ODVhLTg2ZGEtYTBmNTZjZjEzNjU2NTIwNDAwMDA1MzAzOTg2NTgwMkJSNTkyNVBBR0hJUEVSIFNFUlYgT05MSU5FIEVJUkU2MDA5U0FPIFBBVUxPNjIwNzA1MDMqKio2MzA0MDlBRA==",
        ],
        "http_code" => "201",
    ];

    fakePixResponse(StatusPix::END_POINT, 'status_request', $result);

    $status = (new PagHiper())->pix(Cast::Response)->status('BPV661O7AVLORCN5');

    expect($status)
        ->toBeInstanceOf(Response::class)
        ->and($status->json('status_request'))
        ->toBe($result);
});

it('should be able to throw exception due response reject', function () {
    $this->expectException(PagHiperRejectException::class);
    $this->expectExceptionMessage("token ou apiKey inválidos");

    $result = [
        'result'           => 'reject',
        'response_message' => 'token ou apiKey inválidos',
    ];

    fakePixResponse(StatusPix::END_POINT, 'status_request', $result);

    (new PagHiper())->pix()->status('BPV661O7AVLORCN5');
});
