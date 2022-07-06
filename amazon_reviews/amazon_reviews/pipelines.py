# Define your item pipelines here
#
# Don't forget to add your pipeline to the ITEM_PIPELINES setting
# See: https://docs.scrapy.org/en/latest/topics/item-pipeline.html

import mysql.connector
from itemadapter import ItemAdapter


class AmazonReviewsPipeline:

    def __init__(self):
        self.conn = None
        self.curr = None
        self.conn = mysql.connector.connect(
            host='localhost',
            user='root',
            passwd='',
            database='sentimental_analysisdb'
        )
        self.curr = self.conn.cursor()


    def process_item(self, item, spider):
        self.store_db(item)
        return item

    def store_db(self, item):
        ratings = item['ratings']
        review_title = item['review_title']
        reviews = item['reviews']
        helpful = item['helpful']
        country = item['country']
        date = item['date']
        image_urls = item['image']
        self.curr.execute("""select a.Product_name as Product_name from (select id, Product_name from products where id like (select max(id) as currDT from products))a;""")
        product_name = self.curr.fetchall()
        product_name = product_name[0][0]

        self.curr.execute("""INSERT INTO amazon_data (`country`, `date`, `helpful`, `image_url`, `ratings`, `review_title`, `reviews`, `product_name`) values (%s,%s,%s,%s,%s,%s,%s,%s) """,
                          (country, date, helpful, image_urls, ratings, review_title, reviews,product_name), multi=False)
        self.conn.commit()
        self.curr.execute("""delete from amazon_data where ratings=0""")
        self.conn.commit()
