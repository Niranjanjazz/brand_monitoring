# This is a sample Python script.

# Press Shift+F10 to execute it or replace it with your code.
# Press Double Shift to search everywhere for classes, files, tool windows, actions, and settings.
import subprocess
import os
import sys
# product_name = sys.argv[1]
# urls = sys.argv[1]
# print(url)
urls = 'https://www.amazon.co.uk/product-reviews/B08V1CSJXZ/ref=cm_cr_othr_d_show_all_btm?ie=UTF8&reviewerType=all_reviews'
os.chdir('C:\\Users\\Niranjan\\PycharmProjects\\Amazon_review_scraper\\amazon_reviews')
cmd =['scrapy','crawl','amazon_scrapper']
subprocess.call(cmd)

# def print_hi(name):
#     # Use a breakpoint in the code line below to debug your script.
#     print(f'Hi, {name}')  # Press Ctrl+F8 to toggle the breakpoint.
#
#
# # Press the green button in the gutter to run the script.
# if __name__ == '__main__':
#     print_hi('PyCharm')
#     subprocess.Popen(['scrapy','crawl','amazon_scrapper'])

# See PyCharm help at https://www.jetbrains.com/help/pycharm/
