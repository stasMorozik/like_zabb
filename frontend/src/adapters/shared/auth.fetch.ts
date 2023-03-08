import { Either, left, right } from "@sweet-monads/either";
import { ajax, AjaxResponse, AjaxError } from 'rxjs/ajax';
import { catchError, of, Observable, switchMap } from 'rxjs';

export const authFetch = <T>(data: {
  url: string,
  method: string,
  body?: any
}): Observable<Either<Error, T>> => {
  return ajax({
    url: data.url,
    method: data.method,
    headers: {
      'Content-Type': 'application/json',
    },
    body: data.method == 'GET' ? null : data.body
  }).pipe(
    switchMap((result: AjaxResponse<T>) => {
      return of(right(result.response))
    }),
    catchError((error: AjaxError) => {
      if (error.status == 400) {
        return of(left({message: error.response.message} as Error))
      }

      if (error.status == 401) {
        return ajax<Either<Error, T>>({
          url: '/api/sessions/refresh',
          method: 'GET',
          headers: {
            'Content-Type': 'application/json',
          },
        }).pipe(
          switchMap((_: AjaxResponse<boolean>) => {
            return ajax<Either<Error, T>>({
              url: '/api/users/authorize',
              method: 'GET',
              headers: {
                'Content-Type': 'application/json',
              },
            }).pipe(
              switchMap((result: AjaxResponse<T>) => {
                return of(right(result.response))
              }),
              catchError((error: AjaxError) => {
                return of(left({message: error.response.message} as Error))
              })
            )
          }),
          catchError((error: AjaxError) => {
            return of(left({message: error.response.message} as Error))
          })
        )
      }

      if (error.status == 404) {
        return of(left({message: error.response.message} as Error))
      }

      if (error.status == 500) {
        return of(left({message: error.response.message} as Error))
      }

      return of(left({message: 'Something went wrong'} as Error))
    })
  )
}
