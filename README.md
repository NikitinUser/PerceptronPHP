Простой перцептрон левой пяткой на коленке <br>
Переписан с python кода из видео https://www.youtube.com/watch?v=WFYxpi3O950 <br>
Сделано чтобы понять как работают нейросети
<br><br>

Нейронка предсказывает ответ из простых зависимостей во входных данных:<br>
    <ul>
        <li>В data/first_numbers - пример из видео, то что в строке находится в первом элементе, то и получится на выходе</li>
        <li>В data/second_numbers - переделанный пример то что в строке находится во втором элементе, то и получится на выходе</li>
        <li>В data/bunch_numbers зависимость усложнилась 1 должна получаться если в строке три тройки и две единицы, и с этим оно тоже работает, но не идеально, для решения более сложных примеров, как мне кажется, нужно наращивать слои</li>
    </ul>

1. склонировать проект
2. перейти в дирректорию с проектом
3. cd ./docker
4. sudo docker-compose up --build (sudo docker-compose up для последующих запусков)
5. можно проверять проект
<br><br>

Запускаем контейнер, переходим по localhost:7777 <br>
perceptron/startTraining - для обучения <br>
perceptron/getOutputs - для получения результатов <br>
в data создаем (при желании) папку с тренировочными и новыми данными <br>
в config.php настраиваем на какую папку с данными смотрит проект и тд <br>
