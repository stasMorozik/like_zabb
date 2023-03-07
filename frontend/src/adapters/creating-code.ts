import { Either, left, right } from "@sweet-monads/either";
import { ajax } from 'rxjs/ajax';
import { catchError, of, Subject, Observable, switchMap } from 'rxjs';
import { CreatingCodeUseCase } from '../use-cases/creating-code';
import { SharedDtos } from '../use-cases/shared/dtos';

export namespace CreatingCodeAdapters {
  export class Emiter implements CreatingCodeUseCase.Ports.Emiter {
    constructor(
      private readonly _subject: Subject<Either<Error, boolean>>
    ){}

    emit(either: Either<Error, boolean>): void {
      this._subject.next(either)
    }
  }

  export class Api implements CreatingCodeUseCase.Ports.Api {
    fetch(dto: SharedDtos.Email): Observable<Either<Error, boolean>> {
      return ajax({
        url: '/api/confirmation-codes/',
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: dto
      }).pipe(
        switchMap(() => {
          return of(right(true))
        }),
        catchError((error) => {
          //need middleware or interceptor
          if (error.status == 400) {
            return of(left({message: error.response.message} as Error))
          }
          if (error.status == 404) {
            return of(left({message: 'Not found'} as Error))
          }
          if (error.status == 500) {
            return of(left({message: error.response.message} as Error))
          }
          return of(right(true))
        })
      )
    }
  }
}
