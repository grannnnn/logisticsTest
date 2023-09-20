<?php
abstract class AbstractDelivery{
  protected $base_url;

  public function __construct($base_url){
			$this->base_url = $base_url;
		}

  abstract public function CalculateDeliveryCostAndDate($sourceKlad, $targetKladr, $weight);
}


class FastDelivery extends AbstractDelivery{
  public function CalculateDeliveryCostAndDate($sourceKlad, $targetKladr, $weight){
    // тут должен быть HTTP запрос
    // $response = /*результаты запроса*/

    //эмулируем запрос

    $response['price'] = 12500; //стоимость доставки
    $response['period'] = 12; //кол-во дней

    if (isset($response['error'])) {
           return ['error' => $response['error']];
       }

    return [
      'price' => $response['price'],
      'date' => date('Y-m-d', strtotime('+' . $response['period'] . ' days')),
      'error' =>  null
    ];
  }

}

class SlowDelivery extends AbstractDelivery{
  public function CalculateDeliveryCostAndDate($sourceKlad, $targetKladr, $weight){
    // тут должен быть HTTP запрос
    // $response = /*результаты запроса*/


    $base_price = 150;

    //эмулируем запрос
    $response['coefficient'] = 12.8; //коэффициент
    $response['date'] = "2023-05-06"; //дата доставки

    if (isset($response['error'])) {
           return ['error' => $response['error']];
       }

    return [
      'price' => $base_price*$response['coefficient'],
      'date' => $response['date'],
      'error' =>  null
    ];
  }
}

// Список отправлений
$shipments = [
    ['sourceKladr' => '123456', 'targetKladr' => '789012', 'weight' => 5],
    ['sourceKladr' => '987456', 'targetKladr' => '125604', 'weight' => 10],
    ['sourceKladr' => '148856', 'targetKladr' => '146886', 'weight' => 20],
    // Другие отправления
];

// Создаем экземпляры транспортных компаний
$fastDelivery = new FastDelivery('https://fastdelivery-api.com');
$slowDelivery = new SlowDelivery('https://slowdelivery-api.com');

// Рассчитываем стоимость и дату доставки для каждого отправления
$results = [];
foreach ($shipments as $shipment) {
    $fastResult = $fastDelivery->CalculateDeliveryCostAndDate($shipment['sourceKladr'], $shipment['targetKladr'], $shipment['weight']);
    $slowResult = $slowDelivery->CalculateDeliveryCostAndDate($shipment['sourceKladr'], $shipment['targetKladr'], $shipment['weight']);

    $results[] = [
        'shipments' => $shipment,
        'FastDelivery' => $fastResult,
        'SlowDelivery' => $slowResult
    ];
}

// Выводим результаты
echo "<pre>";
foreach ($results as $rez) {
    var_dump($rez);
}
echo "</pre>";

 ?>
