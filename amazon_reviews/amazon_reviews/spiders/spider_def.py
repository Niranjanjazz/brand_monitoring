import scrapy
from ..items import AmazonReviewsItem
from datetime import datetime
import mysql.connector

import sys
# this class inherited from scrapy.spider
class AmazonSpider(scrapy.Spider):
    mydb = mysql.connector.connect(host='localhost', user='root', passwd='', database='sentimental_analysisdb')
    curr = mydb.cursor()

    query = curr.execute("""select a.Amazon_product_link as amazon_prod_link from (select id, Amazon_product_link from products where id like (select max(id) as currDT from products))a;""")
    urls = curr.fetchall()
    url = urls[0][0]
    print(type(url))
    print(url)

    name = "amazon_scrapper"
    # start_urls = [
    #     'https://www.amazon.co.uk/product-reviews/B08V1CSJXZ/ref=cm_cr_othr_d_show_all_btm?ie=UTF8&reviewerType=all_reviews']
    start_urls = [str(url)]

    print(start_urls)
    def parse(self, response):  # response contains the source code of given url

        items = AmazonReviewsItem()  # creating the instance of the class AmazonReviewsItem
        all_reviews = response.css("#cm_cr-review_list .celwidget")  # css tag which contains all the reviews,
        # ratings, etc

        for review in all_reviews:
            ratings = review.css("span.a-icon-alt::text").extract()
            review_title = review.css(".a-text-bold span::text").extract()
            reviews = review.css(".review-text-content span::text").extract()
            helpful = review.css(".cr-vote-text::text").extract()
            country_date = review.css('.review-date::text').extract()
            image_urls = review.css('img.review-image-tile::attr(src)').getall()

            ratings = str(ratings)
            if ratings == '[]':
                ratings = 0.0
            else:
                ratings = float(ratings[2:4])
            country = str(country_date)
            date = str(country_date)
            if country =='[]':
                country = ''
            else:
                country = country[2:-2]
                country = country[country.index('in')+3:country.index('on')-1]
            if date =='[]':
                date = ''
            else:
                date = date[2:-2]
                date = date[date.index('on')+3:]
                date = datetime.strptime(date, '%d %B %Y').strftime('%Y-%m-%d')
            helpful = str(helpful)
            helpful = helpful[2:-2]
            if len(helpful)==0:
                helpful = 0
            else:
                helpful = helpful[:helpful.index('pe')-1]
                if helpful == 'One':
                    helpful = 1
                else:
                    helpful = helpful

            print(helpful)

            items['ratings'] = ratings
            items['review_title'] = str(review_title)[2:-2]
            items['reviews'] = str(reviews)[2:-2]
            items['helpful'] = str(helpful)
            items['country'] = country
            items['date'] = date
            items['image'] = str(image_urls)[2:-2]

            yield items

        next_page = response.css('li.a-last a::attr(href)').get()
        if next_page is not None:
            yield response.follow(next_page,
                                  callback=self.parse)  # after scrapping the first page the parse will follow the next_page and s=parse the next page
